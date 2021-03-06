<?php

/**
 * ExportController - Interface to chose export format or create modified
 * templates
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Florian Bieringer <florian.bieringer@uni-passau.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Stud.IP
 * @since       3.0
 */
class ExportController extends StudipController {

    function before_filter(&$action, &$args) {
        parent::before_filter($action, $args);

        // Set the title of the page
        PageLayout::setTitle(_('Export'));

        // Load the default layout
        if (Request::isXhr()) {
            $this->set_layout(null);
            $this->set_content_type('text/html;Charset=windows-1252');
        } else {
            $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        }

        // Create argstring to forward
        foreach ($args as $arg) {
            $this->argstring .= "/$arg";
        }
        
        // Bind pluginid and pluginpath
        URLHelper::bindLinkParam('plugin', $null);
        URLHelper::bindLinkParam('path', $null2);
    }

    /**
     * Basic export controller to work on a template
     */
    function index_action() {
        
        // Check if a template was given
        if (!func_num_args()) {
            throw new Exception(_('Kein Template angegeben'));
        }
        
        // Save new template if requested
        if (Request::submitted('create')) {
            $save = new exportDoc();
            $save->loadTemplate(func_get_args());
            $save->editTemplate(Request::getArray('edit'));
            $name = Request::get('templatename') ? : _("Neue Vorlage");
            $save->save(Request::get('format'), $name);
        }

        // Now actually load the document
        $export = new exportDoc();
        $export->loadTemplate(func_get_args());

        $this->formats = $export->getFormats();

        if ($export->isEditable()) {
            $this->templates = array();
            foreach ($export->getSavedTemplates() as $template) {
                $tmp['delete'] = $this->url_for("export/removeTemplate/" . $template->id . $this->argstring, $this->pluginParams());
                $tmp['export'] = $this->url_for("export/exportTemplate", $template->id);
                $tmp['name'] = $template->name;
                $tmp['format'] = $template->format;
                $this->templates[] = $tmp;
            }
            $this->preview = $export->preview();
            foreach ($this->formats as $format) {
                $this->exportlink[$format] = $this->url_for("export/export/" . $export->template . $export->getParamString() . "?format=" . $format);
            }
            $this->templating = true;
        } else {
            if (count($this->formats) == 1) {
                $export->export($this->formats[0]);
                die;
            }
        }
    }

    /**
     * Export controller to actually export a template
     */
    function export_action() {
        $export = new exportDoc();
        $export->loadTemplate(func_get_args());
        $export->export(Request::get('format'));
        $this->render_nothing();
    }

    /**
     * Action to export a presaved template
     * 
     * @param int $id Id of the saved template
     */
    function exportTemplate_action($id) {
        $export = new exportDoc($id);
        $export->loadSavedTemplate();
        $export->export();
        $this->render_nothing();
    }

    /**
     * Action to remove a template
     */
    function removeTemplate_action($id) {
        $export = new exportDoc($id);
        $export->delete();
        $this->redirect("export/index/" . join("/", array_slice(func_get_args(), 1))."?plugin=".Request::get('plugin')."&path=".Request::get('path'));
    }

    // customized #url_for for plugins
    function url_for($to) {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map("urlencode", $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join("/", $args));
    }
    
    private function pluginParams() {
        return array("plugin" => Request::get('plugin'), "path" => Request::get('path'));
    }
    
}