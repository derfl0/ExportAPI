<?php

require 'bootstrap.php';

/**
 * DozentenrechtePlugin.class.php
 *
 * ...
 *
 * @author  Florian Bieringer <florian.bieringer@uni-passau.de>
 * @version 0.1a
 */
class ExportAPI extends StudIPPlugin implements SystemPlugin {

    public function __construct() {
        parent::__construct();
    }

    public function initialize() {
    }

    public function perform($unconsumed_path) {
        $this->setupAutoload();
        $dispatcher = new Trails_Dispatcher(
                $this->getPluginPath(), rtrim(PluginEngine::getLink($this, array(), null), '/'), 'show'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }
    
    public static function link($template, $plugin = null, $path = null) {
        return PluginEngine::getURL(__CLASS__, array('plugin' => $plugin, 'path' => $path), "export");
    }

    private function setupAutoload() {
        if (class_exists("StudipAutoloader")) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/lib');
            StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        } else {
            spl_autoload_register(function ($class) {
                        include_once __DIR__ . $class . '.php';
                    });
        }
    }
}
