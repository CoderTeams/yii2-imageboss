<?php

/**
 * Renders IMG tags with the ImageBoss third party service acording to device screen size.
 * @author Pablo Nuñez <pablo@coderteams.com>
 * @copyright Copyright &copy; CoderTeams 2018
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version 1.0.0
 */

namespace coderteams\imageboss;

use yii\helpers\Html;
use Detection\MobileDetect;
use yii\helpers\Url;

class Image extends \yii\base\Widget
{
    CONST IMAGEBOSS_URL = "https://img.imageboss.me";
    public $withoutEnlargement = true;
    public $MaxWidthForMobile = '479';
    public $MaxWidthForTablet = '767';
    public $MaxWidthForDesktop = '991';

    public static function widget($config = [])
    {
        $image = new Image();
        return $image->echo($config["url"], $config);
    }

    public function echo($url, $config = [])
    {
        $this->TrySetProperties($config);

        $size = $this->GetSize();

        $this->withoutEnlargement = $this->withoutEnlargement ? "true" : "false";
        $source = join([
            self::IMAGEBOSS_URL,
            $size,
            "/withoutEnlargement:" . $this->withoutEnlargement . "/",
            Url::to('/', true),
            $url,
        ]);
        return Html::img($source, [
            'onerror' => "this.onerror=null;this.src='" . $url . "';"
        ]);
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
    }

}
