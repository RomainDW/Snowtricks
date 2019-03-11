<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/9/19
 * Time: 8:51 PM.
 */

namespace App\Responder;

use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailSentRedirectResponder
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * MailSentRedirectResponder constructor.
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(User $user)
    {
        return new RedirectResponse($this->urlGenerator->generate('app_mail_sent', ['id' => $user->getId()]));
    }


}
