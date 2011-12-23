M.local_configeditor = {

    Y: null,

    ajaxurl: M.cfg.wwwroot+'/local/configeditor/ajax.php',

    loader: null,

    menuplugin: null,

    menusetting: null,

    valueinput: null,

    savebutton: null,

    enableevents: new Array(),

    init: function(Y) {
        this.Y = Y;
        this.loader = Y.one('#configeditor_loading');
        this.menuplugin = Y.one('#menuplugin');
        this.menusetting = Y.one('#menusetting');
        this.valueinput = Y.one('#valueinput');
        this.savebutton = Y.one('#savebutton');

        this.menuplugin.on('change', function(e) {
            M.local_configeditor.get_settings();
        });
        this.menuplugin.on('keypress', function(e) {
            M.local_configeditor.get_settings();
        });

        this.menusetting.on('change', function(e) {
            M.local_configeditor.get_setting();
        });
        this.menusetting.on('keypress', function(e) {
            M.local_configeditor.get_setting();
        });

        this.savebutton.on('click', function(e) {
            e.preventDefault
            M.local_configeditor.save_setting();
        });
    },

    get_settings: function() {
        Y = this.Y;
        this.show_loader();
        this.detach_enabler();
        var plugin = this.menuplugin.get('value');
        Y.io(this.ajaxurl, {
            data: 'function=get_settings&plugin='+plugin,
            method: 'get',
            on: {
                success: function(id, o) {
                    response = Y.JSON.parse(o.responseText);
                    var list = Y.Node.create('<select id="menusetting" class="select menusetting" name="setting" />');
                    list.appendChild(Y.Node.create('<option selected="selected" value="">Choose...</option>'));
                    for (s in response.settings) {
                        option = Y.Node.create('<option value="'+response.settings[s]+'">'+response.settings[s]+'</option>')
                        list.appendChild(option);
                    }
                    Y.one('#menusetting').replace(list);
                    Y.one('#menusetting').on('change', function(e) {
                        M.local_configeditor.get_setting();
                    });
                    M.local_configeditor.valueinput.set('value', '');
                    M.local_configeditor.disable_button();
                    M.local_configeditor.hide_loader();
                }
            }
        });
    },

    get_setting: function() {
        Y = this.Y;
        this.show_loader();
        this.detach_enabler();
        var plugin = this.menuplugin.get('value');
        var setting = Y.one('#menusetting').get('value');
        Y.io(this.ajaxurl, {
            data: 'function=get_setting&plugin='+plugin+'&setting='+setting,
            method: 'get',
            on: {
                success: function(id, o) {
                    response = Y.JSON.parse(o.responseText);
                    ce = M.local_configeditor;
                    ce.valueinput.set('value', response.setting);
                    ce.disable_button();
                    ce.enableevents.push(ce.valueinput.on('change', ce.enable_button));
                    ce.enableevents.push(ce.valueinput.on('keypress', ce.enable_button));
                    ce.hide_loader();
                }
            }
        });
    },

    save_setting: function() {
        Y = this.Y
        this.show_loader();
        var plugin = this.menuplugin.get('value');
        var setting = Y.one('#menusetting').get('value');
        var value = this.valueinput.get('value');
        Y.io(this.ajaxurl, {
            data: 'function=save_setting&plugin='+plugin+'&setting='+setting+'&value='+value,
            method: 'get',
            on: {
                success: function(id, o) {
                    response = Y.JSON.parse(o.responseText);
                    console.log(response.result);
                    M.local_configeditor.disable_button();
                    M.local_configeditor.hide_loader();
                }
            }
        });
    },

    show_loader: function() {
        this.loader.setStyle('display', 'inline');
    },

    hide_loader: function() {
        this.loader.setStyle('display', 'none');
    },

    disable_button: function() {
        this.savebutton.setAttribute('disabled', 'disabled');
    },

    enable_button: function() {
        M.local_configeditor.savebutton.removeAttribute('disabled');
    },

    detach_enabler: function() {
        if(this.enableevents) {
            for (e in this.enableevents) {
                this.enableevents[e].detach;
            }
        }
    }



}
