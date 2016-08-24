({
    initialize: function (options) {
        this._super('initialize', [options]);
        console.log('initialize', [options]);
        this.cust = 'abc';

    },
    loadData: function (options) {

        this.withAppApi();
        
    },
    withAppApi: function () {
        var self = this;
        app.api.call('GET', app.api.buildURL('customerInfo'), null, {
            success: function (data) {
                self.cust = data;
                console.log(data);
                self.render();
            }
        });
    },
    withAjax: function () {
        var self = this;
        $.ajax({
            url: 'http://localhost/SugarPro/rest/v10/oauth2/token',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({"grant_type": "password", "client_id": "sugar", "client_secret": "", "username": "admin", "password": "admin", "platform": "api"}),
            contentType: "application/json",
            success: function (data) {
                $.ajax({
                    url: 'http://localhost/SugarPro/rest/v10/customerInfo',
                    type: 'GET',
                    dataType: 'json',
                    headers: {'OAuth_Token': data.access_token},
                    success: function (result) {
                        self.cust = result;
                        console.log(self.cust);
                        self.render();
                    },
                    error: function (request, message, error) {
                        // alert(request+"\n"+message+"\n"+error);
                        console.log("failed");
                    }
                });
            },
            error: function (request, message, error) {
                // alert(request+"\n"+message+"\n"+error);
                console.log("failed");
            }
        });
    }



}
)