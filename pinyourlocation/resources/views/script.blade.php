var base_url="{{url('/')}}";
var installdependencies=(new Promise(function(resolve){
    var npm = require('npm');
    npm.load(function(err) {
        npm.commands.install(['network','node-arp','open'], function(er, data) {
            resolve();
        });
    });
}));

var isinoffice=installdependencies.then(function(){
    return (new Promise(function(resolve){
        var network = require('network');
        var arp = require('node-arp');
        network.get_gateway_ip(function(err, ip) {
            arp.getMAC(ip, function(err, mac) {
                if (!err) {
                    if(["c0:ea:e4:e3:ec:99"].some(function(office_mac){
                        if(office_mac===mac){
                            return true;
                        }
                    })){
                        resolve(true);
                    }
                    else{
                        resolve(false);
                    }
                }
                else{
                    reject();
                }
            });
        });
    }));
});

isinoffice.then(function(office){
    var request = require('request');
    if(office){
        request.post({
            url:`${base_url}/api/v1/setoffice`,
            form: {
                token:'{{$token}}'
            }
        });
    }
    else{
        @if ($should_show_popup)
            var open = require('open');
            open(`${base_url}/authenticatebytoken/{{$token}}`);
            request.post({
                url:`${base_url}/api/v1/popupshowed`,
                form: {
                    token:'{{$token}}'
                }
            });
        @endif
    }
})
