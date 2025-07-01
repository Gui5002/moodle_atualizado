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

/*
 * @package     atto_sharing
 * @copyright   2021 Brain station 23 ltd. <https://brainstation-23.com/>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * @module moodle-atto_sharing-button
 * @namespace M.atto_sharing
 * @class Button
 * @extends M.editor_atto.EditorPlugin
 */

var COMPONENTNAME = 'atto_sharing',
    // @codingStandardsIgnoreStart
    IMAGETEMPLATE = '<img src="{{base64sharing}}" alt="" />',
    TEMPLATES = '<form class="mform atto_form atto_sharing" id="atto_sharing_form">' +
        '<div style="display: flex">'+
        '<div className="form-checkbox defaultsnext mr-2" style="margin-right: 5px;">' +
        '<input type="checkbox" name="facebook" value="facebook" id="facebook">' +
        '</div><div className="form-defaultinfo text-muted ">'+M.str.atto_sharing.facebook+'</div></div>' +
        '<div style="display: flex">'+
        '<div className="form-checkbox defaultsnext mr-2" style="margin-right: 5px;">' +
        '<input type="checkbox" name="twiter" value="twiter" id="twiter">' +
        '</div><div className="form-defaultinfo text-muted ">'+M.str.atto_sharing.twiter+'</div></div>' +
        '<div style="display: flex">'+
        '<div className="form-checkbox defaultsnext mr-2" style="margin-right: 5px;">' +
        '<input type="checkbox" name="linkedin" value="linkedin" id="linkedin">' +
        '</div><div className="form-defaultinfo text-muted ">'+M.str.atto_sharing.linkedin+'</div></div>' +
        '<label for="url">'+M.str.atto_sharing.url+'</label>' +
        '<input class="mb-2 form-control fullwidth" name="url" type="url" id="url" required value=""/>' +
        '<button class="btn btn-secondary submit" type="submit">'+M.str.atto_sharing.insertsharing+'</button>' +
        '</form>',
    THUMBIMAGE = '';
// @codingStandardsIgnoreEnd

Y.use('core/event')
    .namespace('M.atto_sharing').Button = Y.Base
    .create('button', Y.M.editor_atto.EditorPlugin, [], {
        /**
         * A reference to the current selection at the time that the dialogue
         * was opened.
         *
         * @property _currentSelection
         * @type Range
         * @private
         */
        _currentSelection: null,
        /**
         * Add event listeners.
         *
         * @method initializer
         */

        initializer: function() {

            // If we don't have the capability to view then give up.
            if (this.get('disabled')) {
                return;
            }

            this.addButton({
                icon: 'sharing',
                iconComponent: COMPONENTNAME,
                callback: this._handleQrCodeGenerator,
                callbackArgs: 'sharing'
            });
        },

        /**
         * Handle sharing video contetn import to text area
         * @method _handleQrCodeGenerator
         * @private
         */
        _handleQrCodeGenerator: function() {

            var dialogue = this.getDialogue({
                headerContent: M.util.get_string('insertsharing', COMPONENTNAME),
                focusAfterHide: true,
                width: 660
            });

            dialogue.set('bodyContent', this._getDialogueContent(this.get('host').getSelection())).show();
            // M.form.shortforms({formid: 'atto_sharing_form'});
        },

        /**
         * Returns the dialogue content for the tool.
         *
         * @method _getDialogueContent
         * @param  {WrappedRange[]} selection Current editor selection
         * @return {Y.Node}
         * @private
         */
        _getDialogueContent: function(selection) {
            var context = {
                facebook: this.get('facebook'),
                twiter: this.get('twiter'),
            };
            var content = Y.Node.create(
                Y.Handlebars.compile(TEMPLATES)(context)
            );
            return this._attachEvents(content, selection);
        },
        /**
         * Attaches required events to the content node.
         *
         * @method _attachEvents
         * @param  {Y.Node}         content The content to which events will be attached
         * @param  {WrappedRange[]} selection Current editor selection
         * @return {Y.Node}
         * @private
         */
        _attachEvents: function(content, selection) {
            content.one('.submit').on('click', function(e) {
                e.preventDefault();
                var facebook = '';
                var twiter = '';
                var linkedin = '';
                var url = '';
                if(document.getElementById('facebook').checked) {
                    facebook = 'facebook';
                }if(document.getElementById('twiter').checked) {
                    twiter = 'twiter';
                }if(document.getElementById('linkedin').checked) {
                    linkedin = 'linkedin';
                }
                url = document.getElementById('url').value;
                if(url == null || url== ''){
                    alert('Url Required');
                    return;
                }
                this._getMediaHTMLSharing(selection,facebook,twiter,linkedin,url);
            }, this);

            return content;
        },
        /**
         * Returns the HTML to be inserted to the text area for the link tab.
         *
         * @method _getMediaHTMLLink
         * @param  {Y.Node} tab The tab from which to extract data
         * @return {String} The compiled markup
         * @private
         */
        _getMediaHTMLSharing: function(selection,facebook,twiter,linkedin,url) {
            var parentContext = this;
            var mediaHTML='';
            if (facebook === 'facebook'){
                mediaHTML +='<span class="ml-2 rounded badge badge-primary color-white ">' +
                    '<a target="_blank" style="color: #ffffff;" name="fb_share" type="button"  target="_blank" ' +
                    'href="https://www.facebook.com/sharer/sharer.php?u='+url+'&t=TEst"><i class="fa fa-facebook"></i> Facebook </a>' +
                    '</span>';
            }
            if (twiter === 'twiter'){
                mediaHTML +='<span style="background: #55acee" class="ml-2 rounded badge color-white ">' +
                    '<a style="color: #ffffff;" target="_blank" type="button" role="button" title="Share on twitter"\n' +
                    '   href="https://twitter.com/intent/tweet?url='+url+'"\n' +
                    '   rel="noopener">\n' +
                    '  <i class="fa fa-twitter"></i>\n' +
                    ' Twiter</a></span>';
            }
            if (linkedin === 'linkedin'){
                mediaHTML +='<span class="ml-2 rounded badge badge-primary color-white "><a style="color:#ffffff;" ' +
                    'href="https://www.linkedin.com/sharing/share-offsite/?url='+url+'" target="_blank">' +
                    '<i class="fa fa-linkedin" aria-hidden="true"></i> Linkedin</a></span>';
            }
                var host = parentContext.get('host');

                parentContext.getDialogue({
                    focusAfterHide: null
                }).hide();
                // eslint-disable-next-line promise/always-return
                if (mediaHTML) {
                    host.setSelection(selection);
                    host.insertContentAtFocusPoint(mediaHTML);
                    parentContext.markUpdated();
                }
        }
    });

