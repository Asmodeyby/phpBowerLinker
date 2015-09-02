<?php

class Helper_Bower {
    public $bower_path = null;

    private $bower_json = null;
    private $bower_components_path = null;

    private $_proccessed_assets = array();
    private $_prepared_assets = array();
    private $_raw_assets = array();

    function __construct($bower_path, $bower_components_path) {
        if (file_exists($bower_path)) {
            $this->bower_path = $bower_path;
            $this->bower_components_path = $bower_components_path;
        } else {
            throw new Exception('Bower file not exist: :'.$bower_path, 500);
        }
    }

    function getAssets() {
        $this->bower_json = json_decode(file_get_contents($this->bower_path), true);
        if (isset($this->bower_json['dependencies'])) {
            foreach($this->bower_json['dependencies'] as $dep_name => $dep_version) {
                $local_bower_path = $this->bower_components_path.$dep_name."/bower.json";
                $local_bower_json = json_decode(file_get_contents($local_bower_path), true);

                if (is_array($local_bower_json["main"])) {
                    foreach($local_bower_json["main"] as $f) {
                        $asset = $this->bower_components_path.$dep_name.$f;
                        $asset = str_replace("./", "/", $asset);
                        $this->_raw_assets[] = $asset;
                    }
                } else {
                    if ($local_bower_json["name"]=="jquery") {
                        if (mb_substr($local_bower_json["main"], 0, 1)!="/") {
                            $local_bower_json["main"] = "/".$local_bower_json["main"];
                        }
                    }
                    $asset = $this->bower_components_path.$dep_name.$local_bower_json["main"];
                    $asset = str_replace("./", "/", $asset);
                    $this->_raw_assets[] = $asset;
                }
            }
        }
        return $this->_raw_assets;
    }

    function prepareAssets() {
        foreach($this->_raw_assets as $currentAsset) {
            $info = pathinfo($currentAsset);
            $this->_prepared_assets[] = $info;
        }
    }

    function processAssets() {
        foreach($this->_prepared_assets as $currentAsset) {
            if ($currentAsset["extension"]=="js") {
                $this->_proccessed_assets[$currentAsset["basename"]] = '<script type="text/javascript" src="' . $currentAsset["dirname"] . '/' . $currentAsset["basename"] . '"></script>';
            } else if ($currentAsset["extension"]=="css") {
                $this->_proccessed_assets[$currentAsset["basename"]] = '<link href="' . $currentAsset["dirname"] . '/' . $currentAsset["basename"] . '" rel="stylesheet">';
            }
        }
        return $this->_proccessed_assets;
    }
}