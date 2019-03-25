<?php declare(strict_types=1);

namespace App\Controller\GUI\AdminPanel;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Handles summary view of admin panel
 *
 * @package App\Controller\GUI\AdminPanel
 */
class SummaryController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/admin", name="gui__admin_summary")
     */
    public function index(): Response
    {
        return $this->render('admin/panels/summary.html.twig');
    }
}
