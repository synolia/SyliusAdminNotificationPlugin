<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Controller;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class ShowAction extends AbstractController
{
    private RepositoryInterface $notificationRepository;

    public function __construct(RepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function __invoke(int $id): Response
    {
        $resource = $this->notificationRepository->find($id);

        return $this->render('@SynoliaSyliusAdminNotificationPlugin/show.html.twig', [
            'resource' => $resource,
        ]);
    }
}
