// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Atto HTML editor
 *
 * @package    atto_reciteditor
 * @copyright  2019 RECIT
 * @license    {@link http://www.gnu.org/licenses/gpl-3.0.html} GNU GPL v3 or later
 */
/**
     * @module moodle-atto_reciteditor-button
     */
    
    /**
     * Atto text editor vvvebjs plugin.
     *
     * @namespace M.atto_reciteditor
     * @class button
     * @extends M.editor_atto.EditorPlugin
     */

Y.namespace('M.atto_reciteditor').Button = Y.Base.create('button', Y.M.editor_atto.EditorPlugin, [], {
    /**
     * Add the buttons to the toolbar
     *
     * @method initializer
     */
    initializer: function() {
        this.addButton({
            title: 'htmleditor',
            icon: 'html',
            iconComponent: 'atto_reciteditor',
            callback: this.openHtmlEditor,
            buttonName: 'htmleditor'
        });
    },

    globalVars: {popup: null},

    openHtmlEditor: function(e) {
        e.preventDefault();
       
        // if the reference exists and the window is not closed so we can bring it to the front with the method focus() method without having to recreate the window
        if(this.globalVars.popup !== null && !this.globalVars.popup.closed){
            this.globalVars.popup.focus();
            return;
        }

        var that = this;
       
        var url = M.cfg.wwwroot;
        url += "/lib/editor/atto/plugins/reciteditor/view.php";
        
        this.globalVars.popup = window.open(url,'HTML Bootstrap Editor','scrollbars=1');

        /*if (this.globalVars.popup.outerWidth < screen.availWidth || this.globalVars.popup.outerHeight < screen.availHeight){
            this.globalVars.popup.moveTo(0,0);
            this.globalVars.popup.resizeTo(screen.availWidth, screen.availHeight);
        }*/

        this.globalVars.popup.attoInterface = {};

        this.globalVars.popup.attoInterface.getSettings = function(){
            var result = {};
            result.contextid = M.cfg.contextid;
            result.wwwroot = M.cfg.wwwroot;
            result.theme = M.cfg.theme;
            result.themerev = M.cfg.themerev;
            result.sesskey = M.cfg.sesskey;
            return result;
        }

        this.globalVars.popup.attoInterface.getThemeCssRules = function(returnAllRules){
            var styleSheets = window.document.styleSheets;


            var cssRulesBuffer = {rules: [], url: []};
            var titles = M.recit.reciteditor.settings.stylesheet_to_add
            if (titles){
                titles = titles.split(',')
            }else{
                titles = []
            }
            for(var sheet of styleSheets){
                // the only css rules we are looking for is the current theme or some custom css from theme
                if((sheet.href && sheet.href.includes(`/theme/styles.php/${M.cfg.theme}`)) || (titles.includes(sheet.title))){

                    if (sheet.href == null || returnAllRules){
                        for(var rule of sheet.rules){
                            cssRulesBuffer.rules.push(rule);
                        }
                    }
                    if (sheet.href){
                        cssRulesBuffer.url.push(sheet.href);
                    }
                }
            }

            return cssRulesBuffer;
        }

        this.globalVars.popup.attoInterface.getThemeUrl = function(){
            return `${M.cfg.wwwroot}/theme/styles.php/${M.cfg.theme}/${M.cfg.themerev}_${M.recit.reciteditor.settings.currentthemesubrev}/all`;
        }

        this.globalVars.popup.attoInterface.getFileTransferData = function(){
            var host = that.get('host');
            var options = host.get('filepickeroptions').image || {};
            
            var result = {};
            result.repo_id = 0 || 0;
            result.client_id = options.client_id || 0;
            result.env = options.env || '';
            result.license = options.defaultlicense || '';
            result.itemid = options.itemid || 0;
            result.author = options.author || '';

            var attr = '';
            for(attr in options.repositories){
                if (options.repositories[attr].type === 'upload') {
                    result.repo_id = options.repositories[attr].id;
                    break;
                }
            }

            for(attr in options.licenses){
                if (options.licenses[attr].shortname === 'cc') { // creative commons
                    result.license = options.licenses[attr].shortname;
                    break;
                }
            }

            return result;
        };

        this.globalVars.popup.attoInterface.getContent = function(){
            return that.editor.getHTML();
        };

        this.globalVars.popup.attoInterface.setContent = function(htmlStr){
            that.editor.setHTML(htmlStr);
            that.globalVars.popup.close();
        };

        this.globalVars.popup.M = M;
    },
});
