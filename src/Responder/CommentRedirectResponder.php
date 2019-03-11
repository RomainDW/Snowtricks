<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/9/19
 * Time: 10:22 PM.
 */

namespace App\Responder;

use App\Domain\Entity\Trick;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommentRedirectResponder
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * CommentRedirectResponder constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Trick $trick
     * @return RedirectResponse
     */
    public function __invoke(Trick $trick)
    {
        return new RedirectResponse($this->urlGenerator->generate('app_show_trick', ['slug' => $trick->getSlug(), '_fragment' => 'comments']));
    }
}
