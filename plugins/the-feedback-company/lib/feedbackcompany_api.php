<?php
	/**
	 * Class for communicating with the Feedback Company oauth API
	 */
	class feedbackcompany_api
	{
		// default expire times for tokens and caches
		private $expire_days_token = 20;
		private $expire_hours_summary = 1;
		private $expire_days_questions = 20;
		private $expire_days_shopsummary = 1;
		private $expire_days_reviews = 1;
		private $expire_days_testimonial = 20;

		// $this->ext is an extension object, with localized functions
		// for interacting with Wordpress
		public $ext = '';

		/**
		 * Constructor is called with instance of 'feedbackcompany_api_ext_wp' as argument
		 *
		 * @param feedbackcompany_api_ext_wp $ext_obj
		 */
		function __construct($ext_obj)
		{
			$this->ext = $ext_obj;

			// get access token if we haven't got one yet
			if (!$this->ext->get_option('feedbackcompany_access_token'))
				$this->oauth_refreshtoken();
		}

		/**
		 * Function for oauth authentication
		 *
		 * Gets a new access token if we don't have one or if our current isn't valid anymore
		 */
		function oauth_refreshtoken()
		{
			if (!$this->ext->get_option('feedbackcompany_oauth_client_id')
				|| !$this->ext->get_option('feedbackcompany_oauth_client_secret'))
				return;

			// don't attempt another call if there was a failed call in the last 1800 seconds (half hour)
			// note last failed call time is reset via this::clear_cache() from admin.php if oauth is changed
			$lastfailedcall = $this->ext->get_cache('lastfailedcall');
			if ($lastfailedcall !== false && time() < $lastfailedcall + 1800)
				return;

			$url = 'https://www.feedbackcompany.com/api/v2/oauth2/token'
				. '?client_id='.$this->ext->get_option('feedbackcompany_oauth_client_id')
				. '&client_secret='.$this->ext->get_option('feedbackcompany_oauth_client_secret')
				. '&grant_type=authorization_code';

			$result = $this->http_request($url);

			// store access token if successful and check account
			if (isset($result->access_token))
			{
				$this->ext->update_option('feedbackcompany_access_token', $result->access_token);

				// check if product reviews are enabled
				$this->check_product_reviews_enabled();
			}
			// if not, register the time of this failed call
			else
				$this->ext->set_cache('lastfailedcall', time(), 1800);
		}

		/**
		 * Function for making api calls
		 *
		 * @param string $url - the URL to call
		 * @param array $postdata - the data to POST
		 * @param bool $retry - set to false on recursive requests to prevent looping
		 */
		function http_request($url, $postdata = '', $method = null, $retry = true)
		{
			// if oauth credentials aren't present, stop
			if (!$this->ext->get_option('feedbackcompany_oauth_client_id')
				|| !$this->ext->get_option('feedbackcompany_oauth_client_secret'))
			{
				return false;
			}

			$args = array();
			$args['headers'] = array();

			// add token to request
			if ($this->ext->get_option('feedbackcompany_access_token'))
				$args['headers']['Authorization'] = 'Bearer '.$this->ext->get_option('feedbackcompany_access_token');

			// add the postdata
			$encoded_postdata = "";
			if ($postdata)
			{
				if (!$method)
					$method = 'POST';

				$encoded_postdata = json_encode($postdata);
				$args['headers']['Content-Type'] = 'application/json; charset=utf-8';
				$args['body'] = $encoded_postdata;
			}

			// default method is GET
			if (!$method)
				$method = 'GET';

			// set method
			$args['method'] = $method;

			// make the call
			$http_response = wp_remote_request($url, $args);

			// if no response due to HTTP error
			if (is_wp_error($http_response))
			{
				$this->ext->log_apierror($url, $method.' '.$encoded_postdata, 'WP_Error: '.$http_response->get_error_message());
				return false;
			}

			// decode response
			$response = json_decode($http_response['body']);

			// if token has somehow become invalid or expired, delete token and retry request
			if (wp_remote_retrieve_response_code($http_response) == 401 && $retry)
			{
				$this->ext->update_option('feedbackcompany_access_token', '');
				$this->oauth_refreshtoken();
				return $this->http_request($url, $postdata, $method, false);
			}

			// log errors if response is not what we expected
			if ($response === null || (isset($response->error) && $response->error === true) || (isset($response->success) && $response->success === false))
				$this->ext->log_apierror($url, $method.' '.$encoded_postdata, $http_response['body']);

			return $response;
		}

		/**
		 * Function checks if product reviews are enabled for this account and stores the result
		 */
		function check_product_reviews_enabled()
		{
			$url = 'https://www.feedbackcompany.com/api/v2/shop';
			$result = $this->http_request($url, '', 'GET');

			if ($result != null && $result != " " && $result->success === true && $result->shop->productReviewsEnabled === true)
			{
				$this->ext->update_option('feedbackcompany_productreviews_enabled', true);
			}
			else
			{
				$this->ext->update_option('feedbackcompany_productreviews_enabled', false);
				foreach (array('product-summary', 'product-extended') as $widget_type)
				{
					delete_option('feedbackcompany_widget_uuid_' . $widget_type);
					delete_option('feedbackcompany_widget_id_' . $widget_type);
				}
			}
		}

		/**
		 * Functions for registering v2 widgets
		 *
		 * @param string $type - the type of widget to register
		 * @param array $options - options of the widget
		 * @param bool $force_refresh - if true forces registering of new widgets
		 */
		function register_widget($type, $options = null, $force_refresh = false)
		{
			$url = 'https://www.feedbackcompany.com/api/v2/widgets';

			$postdata = array('type' => $type);
			if ($options != null)
				$postdata['options'] = $options;

			// if force_refresh is true or if no widget id can be found, register new widgets
			if ($force_refresh || !$this->ext->get_option('feedbackcompany_widget_id_'.$type))
			{
				$result = $this->http_request($url, $postdata);
			}
			// if force_refresh is false or an existing widget id is found, update the widget id
			else
			{
				$url .= '/'.$this->ext->get_option('feedbackcompany_widget_id_'.$type);
				$postdata['uuid'] = $this->ext->get_option('feedbackcompany_widget_uuid_'.$type);
				$postdata['id'] = $this->ext->get_option('feedbackcompany_widget_id_'.$type);
				$result = $this->http_request($url, $postdata, 'PUT');
			}

			// check response and write uuid and id to options
			if (is_object($result) && isset($result->widget) && isset($result->widget->uuid))
			{
				$this->ext->update_option('feedbackcompany_widget_uuid_'.$type, $result->widget->uuid);
				$this->ext->update_option('feedbackcompany_widget_id_'.$type, $result->widget->id);
			}
		}

		/**
		 * Function for registering main (badge) widget
		 *
		 * @param  widget options that are sent directly to Feedback Company API
		 */
		function register_widget_main($size, $amount, $force_refresh)
		{
			$options = array('size' => $size, 'amount_of_reviews' => intval($amount));
			$this->register_widget('main', $options, $force_refresh);
		}

		/**
		 * Function for registering bar widget
		 *
		 * @param  widget options that are sent directly to Feedback Company API
		 */
		function register_widget_bar($force_refresh)
		{
			$options = array();
			$this->register_widget('bar', $options, $force_refresh);
		}

		/**
		 * Function for registering sticky (floating) widget
		 *
		 * @param  widget options that are sent directly to Feedback Company API
		 */
		function register_widget_sticky($force_refresh)
		{
			$options = array();
			$this->register_widget('sticky', $options, $force_refresh);
		}

		/**
		 * Function for registering product summary widget
		 */
		function register_widget_productsummary($force_refresh)
		{
			$this->register_widget('product-summary', null, $force_refresh);
		}

		/**
		 * Function for registering product extended widget
		 *
		 * @param  widget options that are sent directly to Feedback Company API
		 */
		function register_widget_productextended($displaytype, $force_refresh)
		{
			$options = array('display_type' => $displaytype);
			$this->register_widget('product-extended', $options, $force_refresh);
		}

		/**
		 * Functions for outputting v2 widgets
		 *
		 * @param string $type - type of widget
		 * @param string $url_params - custom URL parameters
		 * @param string $template_params - custom template parameters
		 */
		function get_widget($type, $url_params = null, $template_params = null)
		{
			// check if WordPress Multilanguage plugin is enabled
			if (feedbackcompany_wordpressmultilanguage_enabled())
			{
				// get current active language on Wordpress page
				$current_language_active = apply_filters('wpml_current_language', null);
				// get wordpress configured language on feedback company plugin
				$wordpress_configured_language = get_option('feedbackcompany_wordpressmultilanguage');
				// check if the current active language on WordPress page is the same as the configured language
				// if the configured language is not on all and the current language is not equal to configured language than do not render widget
				if ($wordpress_configured_language !== 'all' && $current_language_active !== $wordpress_configured_language)
				{
					return '<!-- Feedback Company Widget omitted because of language mismatch -->';
				};
			}

			// get our widget data
			$data = array();
			$data['uuid'] = $this->ext->get_option('feedbackcompany_widget_uuid_'.$type);
			$data['prefix'] = uniqid();
			$data['version'] = '1.2.1';
			$data['toggle_element'] = $this->ext->get_option('feedbackcompany_productreviewsextendedwidget_toggle_element');

			// if there is no uuid, don't output anything
			if (!$data['uuid'])
				return '';


			// start output
			$out = '<!-- Feedback Company Widget (start) -->';

			// include the Feedback Company javascript if it wasn't included on a previous widget
			static $javascript;
			if (!$javascript)
			{
				$javascript = true;
				$out .= '<script type="text/javascript" src="https://www.feedbackcompany.com/widgets/feedback-company-widget.min.js"></script>';
			}

			// output our widget
			if ($url_params !== null)
				$data['urlParams'] = $url_params;
			if ($template_params !== null)
				$data['templateParams'] = $template_params;

//			$out .=	  '<script type="text/javascript" id="__fbcw__'.$data['prefix'].$data['uuid'].'">'
//				. 'new FeedbackCompanyWidget('.json_encode($data).');'
//				. '</script>'
			$out .=   '<script type="text/javascript" id="__fbcw__'.$data['prefix'].$data['uuid'].'">'
				. '"use strict";!function(){'
				. 'window.FeedbackCompanyWidgets=window.FeedbackCompanyWidgets||{queue:[],loaders:['
				. ']};var options='.json_encode($data).';if('
				. 'void 0===window.FeedbackCompanyWidget){if('
				. 'window.FeedbackCompanyWidgets.queue.push(options),!document.getElementById('
				. '"__fbcw_FeedbackCompanyWidget")){var scriptTag=document.createElement("script")'
				. ';scriptTag.onload=function(){if(window.FeedbackCompanyWidget)for('
				. ';0<window.FeedbackCompanyWidgets.queue.length;'
				. ')options=window.FeedbackCompanyWidgets.queue.pop(),'
				. 'window.FeedbackCompanyWidgets.loaders.push('
				. 'new window.FeedbackCompanyWidgetLoader(options))},'
				. 'scriptTag.id="__fbcw_FeedbackCompanyWidget",'
				. 'scriptTag.src="https://www.feedbackcompany.com/includes/widgets/feedback-company-widget.min.js"'
				. ',document.body.appendChild(scriptTag)}'
				. '}else window.FeedbackCompanyWidgets.loaders.push('
				. 'new window.FeedbackCompanyWidgetLoader(options))}();'
				. '</script>'
				. '<!-- Feedback Company Widget (end) -->';

			return $out;
		}

		/**
		 * Function for outputting badge widget
		 */
		function get_widget_main()
		{
			return $this->get_widget('main');
		}
		function output_widget_main()
		{
			echo $this->get_widget('main');
		}

		/**
		 * Function for outputting bar widget
		 */
		function get_widget_bar()
		{
			return $this->get_widget('bar');
		}
		function output_widget_bar()
		{
			echo $this->get_widget('bar');
		}

		/**
		 * Function for outputting sticky widget
		 */
		function get_widget_sticky()
		{
			return $this->get_widget('sticky');
		}
		function output_widget_sticky()
		{
			echo $this->get_widget('sticky');
		}

		/**
		 * Function for outputting product summary widget
		 *
		 * @params  are sent directly to Feedback Company API
		 */
		function get_widget_productsummary($product_id, $product_name, $product_url, $product_image_url)
		{
			$url_params = array('product_external_id' => $product_id);
			$template_params = array('product_name' => $product_name, 'product_url' => $product_url, 'product_image_url' => $product_image_url);
			return $this->get_widget('product-summary', $url_params, $template_params);
		}

		/**
		 * Function for outputting product extended widget
		 *
		 * @params  are sent directly to Feedback Company API
		 */
		function get_widget_productextended($product_id, $product_name, $product_url, $product_image_url)
		{
			$url_params = array('product_external_id' => $product_id);
			$template_params = array('product_name' => $product_name, 'product_url' => $product_url, 'product_image_url' => $product_image_url);
			return $this->get_widget('product-extended', $url_params, $template_params);
		}

		/**
		 * Function for registering an order with Feedback Company so reminders will be sent
		 *
		 * @param array $orderdata - data for this call, created on woocommerce.php
		 */
		function register_order($orderdata)
		{
			$orderdata['invitation'] = array();

			// set invitation options
			$orderdata['invitation']['delay'] = array(
					'unit' => $this->ext->get_option('feedbackcompany_invitation_delay_unit'),
					'amount' => intval($this->ext->get_option('feedbackcompany_invitation_delay'))
			);

			// request reminders only if they are enabled
			if (get_option('feedbackcompany_invitation_reminder_enabled') !== "0")
			{
				$orderdata['invitation']['reminder'] = array(
					'unit' => $this->ext->get_option('feedbackcompany_invitation_reminder_unit'),
					'amount' => intval($this->ext->get_option('feedbackcompany_invitation_reminder'))
				);
			}
			// if not enabled, set reminder delay to 0 - none will be sent then
			else
			{
				$orderdata['invitation']['reminder'] = array(
					'unit' => 'days',
					'amount' => 0,
				);
			}

			$url = 'https://www.feedbackcompany.com/api/v2/orders';
			return $this->http_request($url, $orderdata);
		}


		/**
		 * functions for legacy widgets below
		 *
		 * these can all be dropped once the legacy widget support is
		 */

		function get_shopsummary()
		{
			$result = $this->ext->get_cache('shopsummary');
			if (!$result)
			{
				$this->update_shopsummary();
				$result = $this->ext->get_cache('shopsummary');
			}
			return $result;
		}
		function update_shopsummary()
		{
			$url = 'https://www.feedbackcompany.com/api/v2/shop/summary/';
			$result = $this->http_request($url);
			if (!isset($result->data))
				return false;

			$this->ext->set_cache('shopsummary', $result, 3600 * $this->expire_days_shopsummary);
		}

		function get_questions()
		{
			$result = $this->ext->get_cache('questions');
			if (!$result)
			{
				$this->update_questions();
				$result = $this->ext->get_cache('questions');
			}
			return $result;
		}
		function update_questions()
		{
			$url = 'https://www.feedbackcompany.com/api/v2/question/';
			$result = $this->http_request($url);
			$this->ext->set_cache('questions', $result, 86400 * $this->expire_days_questions);
		}

		function get_reviews()
		{
			$result = $this->ext->get_cache('reviews');
			if (!$result)
			{
				$this->update_reviews();
				$result = $this->ext->get_cache('reviews');
			}
			return $result;
		}
		function update_reviews()
		{
			$url = 'https://www.feedbackcompany.com/api/v2/review/';
			$result = $this->http_request($url);
			if (!$result || !isset($result->reviews) || count($result->reviews) == 0)
				return;

			$this->ext->set_cache('reviews', $result, 86400 * $this->expire_days_reviews);
		}

		function get_review($id)
		{
			$result = $this->ext->get_cache('testimonial_'.$id);
			if ($result === false)
			{
				$this->update_review($id);
				$result = $this->ext->get_cache('testimonial_'.$id);
			}
			return $result;
		}
		function update_review($id)
		{
			$url = 'https://www.feedbackcompany.com/api/v2/review/get/?id='.$id;
			$result = $this->http_request($url);
			if (!$result || !isset($result->review))
				return;

			$this->ext->set_cache('testimonial_'.$id, $result, 86400 * $this->expire_days_testimonial);
		}

		function get_summary()
		{
			$result = $this->ext->get_cache('summary');
			if (!$result)
			{
				$this->update_summary();
				$result = $this->ext->get_cache('summary');
			}
			return $result;
		}
		function update_summary()
		{
			$url = 'https://www.feedbackcompany.com/api/v2/review/summary/';
			$result = $this->http_request($url);
			if (!isset($result->statistics) || !is_array($result->statistics))
				return false;

			$this->ext->set_cache('summary', $result, 3600 * $this->expire_hours_summary);
		}

		function clear_cache()
		{
			$this->ext->update_option('feedbackcompany_access_token', '');
			$this->ext->delete_cache('lastfailedcall');
			$this->ext->delete_cache('summary');
			$this->ext->delete_cache('reviews');
		}

		function format_star()
		{
			if ($this->ext->get_option('star_type'))
				return '&#'.$this->ext->get_option('star_type').';';
			else
				return '&#9733;';
		}
		function format_star_color()
		{
			return $this->ext->get_option('star_color');
		}
		function format_stars($score_max, $score)
		{
			$star_scale = $this->ext->get_option('star_scale');
			if (intval($star_scale) == 0)
				$star_scale = 5;

			$percent = $score / $score_max * 100;

			$ret = '<span class="feedbackcompany-stars">';
			$ret .= '<span class="feedbackcompany-stars-negative" style="width: '.(100 - $percent).'%;">';
			for ($i = 0; $i < $star_scale; $i++)
				$ret .= $this->format_star();
			$ret .= '</span>';
			$ret .= '<span class="feedbackcompany-stars-positive" style="width: '.$percent.'%;">';
			for ($i = 0; $i < $star_scale; $i++)
				$ret .= $this->format_star();
			$ret .= '</span>';
			$ret .= '</span>';

			return $ret;
		}

		function get_microdata()
		{
			static $executed = false;
			if ($executed)
				return false;

			$microdata = $this->ext->get_options('richsnippet_');
			if (!$microdata['schema'])
				return false;

			$ret = array();
			$ret['open'] = '<span itemscope itemtype="http://schema.org/'.$microdata['schema'].'">';
			unset($microdata['schema']);

			$ret['items'] = '';
			foreach ($microdata as $key => $value)
			{
				if ($value)
				{
					$ret['items'] .= '<meta '
						. 'itemprop="'.$key.'" content="'.$value.'" />'."\n";
				}
			}

			$ret['rating_open'] = '<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
			$ret['rating_value_open'] = '<span itemprop="ratingValue">';
			$ret['rating_value_close'] = '</span>';
			$ret['rating_count_open'] = '<span itemprop="reviewCount">';
			$ret['rating_count_close'] = '</span>';
			$ret['rating_max_open'] = '<span itemprop="bestRating">';
			$ret['rating_max_close'] = '</span>';
			$ret['rating_close'] = '</span>';

			$ret['close'] = '</span>';

			$executed = true;
			return $ret;
		}

		function simple_score()
		{
			$ret = '';

			$shopsummary = $this->get_shopsummary();
			$summary = $this->get_summary();

			if (!isset($summary->statistics) || !is_array($summary->statistics) || !isset($shopsummary->data))
				return false;

			// find question with final score to base widget on
			foreach ($summary->statistics as $q)
				if (isset($q->question_type) && $q->question_type == 'final_score')
					$question = $q;
			// no final score?  stop here
			if (!isset($question))
				return false;

			$microdata = $this->get_microdata();

			$star_scale = $this->ext->get_option('star_scale');
			if (intval($star_scale) == 0)
				$star_scale = 5;

			$score_max = $question->maxscore;
			$score = $question->value;
			$percent = $score / $score_max * 100;

			if ($microdata)
				$ret .= $microdata['open'];

			$ret .= '<span class="feedbackcompany-simple">';
			$ret .= $this->format_stars($score_max, $score);

			if ($microdata)
				$ret .= $microdata['rating_open'];

			$score_scale = $this->ext->get_option('score_scale');
			if ($microdata)
				$ret .= $microdata['rating_value_open'];
			$ret .= str_replace('.', ',', $percent / 100 * $score_scale);
			if ($microdata)
				$ret .= $microdata['rating_value_close'];
			$ret .= ' van ';
			if ($microdata)
				$ret .= $microdata['rating_max_open'];
			$ret .= $score_scale;
			if ($microdata)
				$ret .= $microdata['rating_max_close'];
			$ret .= ' op basis van ';

			if ($microdata)
				$ret .= $microdata['rating_count_open'];
			$ret .= $question->count;
			if ($microdata)
				$ret .= $microdata['rating_count_close'];
			$ret .= ' '.($question->count == 1 ? 'beoordeling' : 'beoordelingen');

			if ($microdata)
				$ret .= $microdata['rating_close'];

			$ret .= ' bij ';
			$ret .= '<a target="_blank" class="feedbackcompany-link" href="'.$shopsummary->data->company_page_url.'">';
			$ret .= 'Feedback Company';
			$ret .= '</a>';
			$ret .= '</span>';

			if ($microdata)
				$ret .= $microdata['items'];

			if ($microdata)
				$ret .= $microdata['close'];

			return $ret;
		}

		function widget_score()
		{
			$ret = '';

			$shopsummary = $this->get_shopsummary();
			$summary = $this->get_summary();

			if (!isset($summary->statistics) || !is_array($summary->statistics) || !isset($shopsummary->data))
				return false;

			// find question with final score to base widget on
			foreach ($summary->statistics as $q)
				if (isset($q->question_type) && $q->question_type == 'final_score')
					$question = $q;
			// no final score?  stop here
			if (!isset($question))
				return false;

			$microdata = $this->get_microdata();

			$star_scale = $this->ext->get_option('star_scale');
			if (intval($star_scale) == 0)
				$star_scale = 5;

			$score_max = $question->maxscore;
			$score = $question->value;
			$percent = $score / $score_max * 100;

			if ($microdata)
				$ret .= $microdata['open'];

			$ret .= '<a target="_blank" class="feedbackcompany-link" href="'.$shopsummary->data->company_page_url.'">';
			$ret .= '<span class="feedbackcompany-widget">';

			$ret .= '<span class="feedbackcompany-widgetheader">';
			$ret .= esc_html($this->ext->get_option('title_score'));
			$ret .= '</span>';

			if ($microdata)
				$ret .= $microdata['rating_open'];

			$ret .= $this->format_stars($score_max, $score);

			$score_scale = $this->ext->get_option('score_scale');
			$ret .= '<span class="feedbackcompany-score">';
			if ($microdata)
				$ret .= $microdata['rating_value_open'];
			$ret .= str_replace('.', ',', $percent / 100 * $score_scale);
			if ($microdata)
				$ret .= $microdata['rating_value_close'];
			$ret .= ' van ';
			if ($microdata)
				$ret .= $microdata['rating_max_open'];
			$ret .= $score_scale;
			if ($microdata)
				$ret .= $microdata['rating_max_close'];
			$ret .= '</span>';

			$ret .= '<span class="feedbackcompany-amount">';
			if ($microdata)
				$ret .= $microdata['rating_count_open'];
			$ret .= $question->count;
			if ($microdata)
				$ret .= $microdata['rating_count_close'];
			$ret .= ' '.($question->count == 1 ? 'beoordeling' : 'beoordelingen');
			$ret .= '</span>';

			if ($microdata)
				$ret .= $microdata['rating_close'];

			$ret .= '<span class="feedbackcompany-source">';
			$ret .= '<div><img alt="Feedback Company" src="'.$this->ext->get_url().'/images/feedbackcompany-logo-81x23.png" '
				. 'srcset="'.$this->ext->get_url().'/images/feedbackcompany-logo-162x46.png 2x"></div>';

			$ret .= '</span>';

			$ret .= '</span>';
			$ret .= '</a>';

			if ($microdata)
				$ret .= $microdata['items'];

			if ($microdata)
				$ret .= $microdata['close'];

			$ret .= '<br style="clear: both;">';

			return $ret;
		}

		function widget_reviews()
		{
			$ret = '';
			$ret_reviews = '';

			$shopsummary = $this->get_shopsummary();
			$reviews = $this->get_reviews();

			if (!isset($reviews->reviews) || count($reviews->reviews) == 0 || !isset($shopsummary->data))
				return;

			$i = 0;
			foreach ($reviews->reviews as $review)
			{
				if ($i >= 10)
					break;

				$main_open = null;
				$final_score = null;

				foreach ($review->questions as $question)
				{
					if (!isset($question->type))
						continue;

					if ($question->type == 'main_open')
						$main_open = $question;
					if ($question->type == 'final_score')
						$final_score = $question;
				}

				if (!$main_open || !$main_open->value || !$final_score)
					continue;

				// TODO - maxscore is hier hard ingesteld op 5... de API geeft in een reviews call geen maxscore mee?

				if ($final_score->value < 4)
					continue;

				$ret_reviews .= '<span id="feedbackcompany-reviewslider'.$i.'">';

				$ret_reviews .= $this->format_stars(5, $final_score->value);

				$ret_reviews .= '<span class="feedbackcompany-reviewauthor">';
				$ret_reviews .= htmlentities($review->client->name);
				$ret_reviews .= '</span>';
				$ret_reviews .= '<span class="feedbackcompany-reviewcontent">';
				$ret_reviews .= htmlentities($main_open->value);
				$ret_reviews .= '</span>';

				$ret_reviews .= '</span>';

				$i++;
			}

			// geen reviews om weer te geven? stop hier
			if (!$ret_reviews)
				return false;

			// widget
			$ret .= '<a target="_blank" class="feedbackcompany-link" href="'.$shopsummary->data->company_page_url.'">';
			$ret .= '<span class="feedbackcompany-widget">';

			$ret .= '<span class="feedbackcompany-widgetheader">';
			$ret .= esc_html($this->ext->get_option('title_reviews'));
			$ret .= '</span>';

			// review output
			$ret .= $ret_reviews;

			// placeholder om hoogte vast te zetten
			$ret .= '<span id="feedbackcompany-placerholder-reviewslider" style="visibility: hidden;">';
			$ret .= $this->format_stars(5, 5);
			$ret .= '<span class="feedbackcompany-reviewauthor"></span>';
			$ret .= '<span class="feedbackcompany-reviewcontent"></span>';
			$ret .= '</span>';

			$ret .= '<span class="feedbackcompany-source">';
			$ret .= '<div><img alt="Feedback Company" src="'.$this->ext->get_url().'/images/feedbackcompany-logo-81x23.png" '
				. 'srcset="'.$this->ext->get_url().'/images/feedbackcompany-logo-162x46.png 2x"></div>';

			$ret .= '</span>';

			$ret .= '</span>';
			$ret .= '</a>';

			$ret .= '<br style="clear: both;">';

			return $ret;
		}

		function widget_testimonial($review_id)
		{
			$ret = '';

			$shopsummary = $this->get_shopsummary();
			$review = $this->get_review($review_id);

			if (!isset($review->review) || !isset($shopsummary->data))
				return;

			$main_open = null;
			$final_score = null;

			foreach ($review->review->questions as $question)
			{
				if (!isset($question->type))
					continue;

				if ($question->type == 'main_open')
					$main_open = $question;
				if ($question->type == 'final_score')
					$final_score = $question;
			}

			if (!$main_open || !$main_open->value || !$final_score)
				return '';

			// TODO - maxscore is hier hard ingesteld op 5... de API geeft in een reviews call geen maxscore mee?

			$ret .= '<a target="_blank" class="feedbackcompany-link" href="'.$shopsummary->data->company_page_url.'">';
			$ret .= '<span class="feedbackcompany-widget">';

			$ret .= '<span class="feedbackcompany-widgetheader">';
			$ret .= esc_html($this->ext->get_option('title_testimonial'));
			$ret .= ' '.trim($review->review->client->name);
			$ret .= '</span>';

			$ret .= $this->format_stars(5, $final_score->value);

			$ret .= '<span class="feedbackcompany-reviewcontent">';
			$ret .= htmlentities($main_open->value);
			$ret .= '</span>';

			$ret .= '<span class="feedbackcompany-source">';
			$ret .= '<div><img alt="Feedback Company" src="'.$this->ext->get_url().'/images/feedbackcompany-logo-81x23.png" '
				. 'srcset="'.$this->ext->get_url().'/images/feedbackcompany-logo-162x46.png 2x"></div>';

			$ret .= '</span>';

			$ret .= '</span>';
			$ret .= '</a>';

			$ret .= '<br style="clear: both;">';

			return $ret;

			$ret .= '<span class="feedbackcompany-amount">';
			$ret .= $question->count.' '.($question->count == 1 ? 'beoordeling' : 'beoordelingen');
			$ret .= '</span>';

			return $ret;
		}
	}
