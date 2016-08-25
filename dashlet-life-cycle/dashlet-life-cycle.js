/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


({
    plugins: ['Dashlet'],
    
    initialize : function(options){
        this._super('initialize', [options]);
        this.name = this.model.attributes.full_name;
        this.id = this.model.attributes.id;
        this.obj='';
        console.log(this.model);
    },
    loadData : function(options){
        
         var self = this;
        app.api.call('GET', app.api.buildURL('customerInfo'), null, {
            success: function (data) {
               for(var i=0; i < data.length; i++){
                  if(!_.isUndefined(data[i].target_id)){
                      if(self.id == data[i].target_id){
                          self.obj = data[i];
                          break;
                      }
                  }
                  if(!_.isUndefined(data[i].lead_id)){
                      if(self.id == data[i].lead_id){
                          self.obj = data[i];
                          break;
                      }
                  }
                  if(!_.isUndefined(data[i].contact_id)){
                      if(self.id == data[i].contact_id){
                          self.obj = data[i];
                          break;
                      }
                  }
                   if(!_.isUndefined(data[i].opportunity_id)){
                      if(self.id == data[i].opportunity_id){
                          self.obj = data[i];
                          break;
                      }
                  }
                 
               }
               
               console.log(self.obj);
               self.render();
            }
        });
    }
    
})
