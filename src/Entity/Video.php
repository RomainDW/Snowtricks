<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Video
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $embed;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $embed__id;

    public function getId()
    {
        return $this->id;
    }

    public function getEmbed(): ?string
    {
        return $this->embed;
    }

    public function setEmbed(string $embed): self
    {
        $this->embed = $embed;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEmbed_Id(): ?string
    {
        return $this->embed__id;
    }

    public function setEmbed_Id(string $embed__id): self
    {
        $this->embed__id = $embed__id;

        return $this;
    }

    private function youtubeId($url)
    {
        $tableaux = explode('=', $url);  // découpe l’url en deux  avec le signe ‘=’

        $this->setEmbed_Id($tableaux[1]);  // ajoute l’identifiant à l’attribut identif
        $this->setType('youtube');  // signale qu’il s’agit d’une video youtube et l’inscrit dans l’attribut $type
    }

    private function dailymotionId($url)
    {
        $cas = explode('/', $url); // On sépare la première partie de l'url des 2 autres

        $idb = $cas[4];  // On récupère la partie qui nous intéressent

        $bp = explode('_', $idb);  // On sépare l'identifiant du reste

        $id = $bp[0]; // On récupère l'identifiant

        $this->setEmbed_Id($id);  // ajoute l’identifiant à l’attribut identif

        $this->setType('dailymotion'); // signale qu’il s’agit d’une video dailymotion et l’inscrit dans l’attribut $type
    }

    private function vimeoId($url)
    {
        $tableaux = explode('/', $url);  // on découpe l’url grâce au « / »

        $id = $tableaux[count($tableaux) - 1];  // on reticent la dernière partie qui contient l’identifiant

        $this->setEmbed_Id($id);  // ajoute l’identifiant à l’attribut identif

        $this->setType('vimeo');  // signale qu’il s’agit d’une video vimeo et l’inscrit dans l’attribut $type
    }

    /**
     * @ORM\PrePersist() // Les trois événement suivant s’exécute avant que l’entité soit enregister
     * @ORM\PreUpdate()
     * @ORM\PreFlush()
     */
    public function extractEmbedId()
    {
        $url = $this->getEmbed();  // on récupère l’url

        if (preg_match('#^(http|https)://www.youtube.com/#', $url)) {  // Si c’est une url Youtube on execute la fonction correspondante
            $this->youtubeId($url);
        } elseif ((preg_match('#^(http|https)://www.dailymotion.com/#', $url))) { // Si c’est une url Dailymotion on execute la fonction correspondante
            $this->dailymotionId($url);
        } elseif ((preg_match('#^(http|https)://vimeo.com/#', $url))) { // Si c’est une url Vimeo on execute la fonction correspondante
            $this->vimeoId($url);
        }
    }
}
