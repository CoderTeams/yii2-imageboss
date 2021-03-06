<?php

/**
 * Renders IMG tags with the ImageBoss third party service acording to device screen size.
 * @author Pablo Nuñez <pablo@coderteams.com>
 * @copyright Copyright &copy; CoderTeams 2018
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version 1.0.1
 */

namespace coderteams\imageboss;

use yii\helpers\Html;
use Detection\MobileDetect;
use yii\helpers\Url;

class Image extends \yii\base\Widget
{
    CONST IMAGEBOSS_URL = "https://img.imageboss.me";
    public $withoutEnlargement = true;
    public $MaxWidthForMobile = '480';
    public $MaxWidthForTablet = '920';
    public $MaxWidthForDesktop = '1920';

    public $url;
    public $options = [];

    public static function widget($config = [])
    {
        $image = new Image();
        return $image->echo($config["url"], $config);
    }

    public function echo($url, $config = [])
    {
        $this->TrySetProperties($config);
        $source = $this->getUrl($url, $config);
        return Html::img($source, 
		array_merge(
		['onerror' => "this.onerror=null;this.src='" . $url . "';"],
		$this->options)
		);
    }

    private function GetSize()
    {
        $mobile_detect = new MobileDetect();
        if ($mobile_detect->isTablet()) {
            return "/width/$this->MaxWidthForTablet";
        } elseif ($mobile_detect->isMobile()) {
            return "/width/$this->MaxWidthForMobile";
        } else {
            return "/width/$this->MaxWidthForDesktop";
        }
    }

    private function TrySetProperties($config = [])
    {
        $this->withoutEnlargement = $config["withoutEnlargement"] ?? $this->withoutEnlargement;
        $this->MaxWidthForMobile = $config["MaxWidthForMobile "] ?? $this->MaxWidthForMobile;
        $this->MaxWidthForTablet = $config["MaxWidthForTablet "] ?? $this->MaxWidthForTablet;
        $this->MaxWidthForDesktop = $config["MaxWidthForDesktop"] ?? $this->MaxWidthForDesktop;
        $this->options = $config["options"] ?? $this->options;
    }


    public function getUrl($url, $config = [])
    {
        $this->TrySetProperties($config);

        $size = $this->GetSize();

        $this->withoutEnlargement = $this->withoutEnlargement ? "true" : "false";
        $result = join([
            self::IMAGEBOSS_URL,
            $size,
            "/withoutEnlargement:" . $this->withoutEnlargement . "/",
            $url,
        ]);
        return $result;
    }


}
