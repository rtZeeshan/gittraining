/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


({
    plugins: ['Dashlet'],
    initialize: function (options) {
        this._super('initialize', [options]);
        this.name = this.model.attributes.name;
        this.id = this.model.attributes.id;
        this.obj = '';
        // console.log("abc",this.context);
        // console.log(this.model);
        this.context.on('button:save_button:click', this.reload_data, this);

    },
    reload_data: function () {
        console.log("reloading");
        //app.router.refresh();
        // var url= '#Opportunities/'+this.model.id;
        //console.log(url);
        //app.router.navigate(url);
        console.log(this.model.attributes.name);
        this.name = this.model.attributes.name;
        this.render();
        console.log("reloaded");
        console.log(this);

    }



})
