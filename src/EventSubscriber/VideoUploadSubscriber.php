<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/22/19
 * Time: 5:13 PM.
 */

namespace App\EventSubscriber;

use App\Event\VideoUploadEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoUploadSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            VideoUploadEvent::NAME => 'onVideoUpload',
        ];
    }

    public function onVideoUpload(VideoUploadEvent $event)
    {
        $video = $event->getVideo();

        $url = $video->getEmbed();  // get the video url

        $embedId = $this->extractEmbedId($url)['id']; // get the url id

        $type = $this->extractEmbedId($url)['type']; // get the video type

        $video->setEmbed_Id($embedId); // set the url id

        $video->setType($type); // set the url id
    }

    private function extractEmbedId($url)
    {
        if (preg_match('#^(http|https)://www.youtube.com/#', $url)) {  // Si c’est une url Youtube on execute la fonction correspondante
            return $this->youtubeId($url);
        } elseif ((preg_match('#^(http|https)://www.dailymotion.com/#', $url))) { // Si c’est une url Dailymotion on execute la fonction correspondante
            return $this->dailymotionId($url);
        } elseif ((preg_match('#^(http|https)://vimeo.com/#', $url))) { // Si c’est une url Vimeo on execute la fonction correspondante
            return $this->vimeoId($url);
        } else {
            return null;
        }
    }

    private function youtubeId($url)
    {
        $explodeUrl = explode('=', $url);  // découpe l’url en deux  avec le signe ‘=’

        $id = $explodeUrl[1];

        $type = 'youtube';

        return [
            'id' => $id,
            'type' => $type,
        ];
    }

    private function dailymotionId($url)
    {
        $cas = explode('/', $url); // On sépare la première partie de l'url des 2 autres

        $idb = $cas[4];  // On récupère la partie qui nous intéressent

        $bp = explode('_', $idb);  // On sépare l'identifiant du reste

        $id = $bp[0]; // On récupère l'identifiant

        $type = 'dailymotion';

        return [
            'id' => $id,
            'type' => $type,
        ];
    }

    private function vimeoId($url)
    {
        $tableaux = explode('/', $url);  // on découpe l’url grâce au « / »

        $id = $tableaux[count($tableaux) - 1];  // on reticent la dernière partie qui contient l’identifiant

        $type = 'vimeo';

        return [
            'id' => $id,
            'type' => $type,
        ];
    }
}
