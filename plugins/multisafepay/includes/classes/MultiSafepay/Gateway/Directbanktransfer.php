<?php

/**
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the MultiSafepay plugin
 * to newer versions in the future. If you wish to customize the plugin for your
 * needs please document your changes and make backups before you update.
 *
 * @category    MultiSafepay
 * @package     Connect
 * @author      MultiSafepay <integration@multisafepay.com>
 * @copyright   Copyright (c) 2020 MultiSafepay, Inc. (http://www.multisafepay.com)
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
 * ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace MultiSafepay\WooCommerce\Gateway;

class Directbanktransfer extends Core
{

    public static function getCode()
    {
        return 'multisafepay_directbanktransfer';
    }

    public static function getName()
    {
        return __('Direct Bank Transfer', 'multisafepay');
    }

    public static function getSettings()
    {
        return get_option('woocommerce_multisafepay_directbanktransfer_settings');
    }

    public static function getTitle()
    {
        $settings = self::getSettings();
        if (!isset($settings['title'])) {
            $settings['title'] = '';
        }

        return ($settings['title']);
    }

    public static function getGatewayCode()
    {
        return 'DBRTP';
    }

    public function getType()
    {
        return 'redirect';
    }
}
