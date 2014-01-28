var TP = TP || {};

/* Revealing Module pattern */
TP.PasswordGenerator = function (){

    var SALT = '§$%&/()=OIUTZFGVBHNHJ',
        $form, 
        $domain,
        $password,
        $key,
        domainValue,
        passwordValue,
        keyValue,

        encObject,
        decObject,

        handleFormSubmit = function (e){

        // OPTIMIZE: deprecated?
        e.preventDefault();

            domainValue = $domain.val();
            passwordValue = $password.val();

            // sjcl.json.encrypt(password, plaintext, params, rp)
            try {
                keyValue = domainValue + ':' + passwordValue;
            } catch (e) {
                window.console.log('ERROR:');
                window.console.log(e);
            }

            $key.text(keyValue);
        },

        init = function () {
            
            $form = $('#form');
            $domain = $('#domain');
            $password = $('#password');
            $key = $('#key');

            $form.on('submit', handleFormSubmit.bind(this));

        };

    return {
        init: init,
        getSalt: function() { return SALT }
    };

}();

$(document).ready(function(){
    TP.PasswordGenerator.init();
});
