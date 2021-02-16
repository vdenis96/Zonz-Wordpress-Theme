function Triangle() {
    this.AB = 0;
    this.BC = 0;
    this.CA = 0;

    this.setSides = function (AB, BC, CA) {
        this.AB = AB;
        this.BC = BC;
        this.CA = CA;
        
        var a, b, c, k1,k2,k3;

        a = this.AB;
        b = this.BC;
        c = this.CA;

        k1 = Math.acos((c * c + b * b - a * a) / (2 * c * b));
        k1 = k1 * 180 / Math.PI;
        
        k2 = Math.acos((c * c + a * a - b * b) / (2 * c * a));
        k2 = k2 * 180 / Math.PI;
        
        k3 = Math.acos((b * b + a * a - c * c) / (2 * b * a));
        k3 = k3 * 180 / Math.PI;
        
        this.A = k1.toFixed(3);
        this.B = k2.toFixed(3);
        this.C = k3.toFixed(3);
    }
    
    this.setInner = function (lenA, lenB, lenC) { //fastening length
        
        var ab,bc,ca,za,zb,zc,ha,hb,hc;

        za = this.A * Math.PI / 360;
        zb = this.B * Math.PI / 360;
        zc = this.C * Math.PI / 360;
        //console.log('za='+za+' zb='+zb+' zc='+zc);

        ha = Math.abs(lenA * Math.cos(za));
        hb = Math.abs(lenB * Math.cos(zb));
        hc = Math.abs(lenC * Math.cos(zc));
        //console.log('ha='+ha+' hb='+hb+' hc='+hc);

        this.ab = (this.AB - ha - hb).toFixed(2);
        this.bc = (this.BC - hb - hc).toFixed(2);
        this.ca = (this.CA - hc - ha).toFixed(2);
        //console.log('ab='+this.ab+' bc='+this.bc+' ca='+this.ca);
    }
}

Triangle.prototype.getArea = function() {
    return (Math.max(this.AB,this.BC,this.CA)*Math.min(this.AB,this.BC,this.CA)/2).toFixed(2);
}
Triangle.prototype.getInnerArea = function() {
    return (Math.max(this.ab,this.bc,this.ca)*Math.min(this.ab,this.bc,this.ca)/2).toFixed(2);
}
Triangle.prototype.getInfoString = function() {
    var datajson = this.getArea()+' m2';
    datajson += '<br> Lengte zijde AB: '+this.AB;
    datajson += '<br> Lengte zijde BC: '+this.BC;
    datajson += '<br> Lengte zijde CA: '+this.CA;
    
    return datajson;
}
Triangle.prototype.getInnerInfoString = function() {
    var datajson = this.getInnerArea()+' m2';
    datajson += '<br> Lengte zijde AB: '+this.ab;
    datajson += '<br> Lengte zijde BC: '+this.bc;
    datajson += '<br> Lengte zijde CA: '+this.ca;
    
    return datajson;
}

var t = new Triangle;
t.setSides(3,4,5);
t.setInner(0.5,0.2,0.1);
t.getInfoString();
t.getInnerInfoString();
console.log(t);

function setInfo() {
    jQ('#area').html(datajson);
    jQ('#area-input').val(datajson);
}