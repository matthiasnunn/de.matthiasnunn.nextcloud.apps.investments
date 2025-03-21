<?php

namespace OCA\Investments\Controller;

use OCA\Investments\AppInfo\Application;
use OCA\Investments\Services\InvestmentsDevelopmentService;
use OCA\Investments\Services\InvestmentsService;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;


class MainController extends Controller
{
    private $investmentsDevelopmentService;
    private $investmentsService;
    private $urlGenerator;


    public function __construct($AppName, InvestmentsDevelopmentService $investmentsDevelopmentService, InvestmentsService $investmentsService, IRequest $request, IURLGenerator $urlGenerator)
    {
        parent::__construct($AppName, $request);

        $this->investmentsDevelopmentService = $investmentsDevelopmentService;
        $this->investmentsService = $investmentsService;
        $this->urlGenerator = $urlGenerator;
    }


    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function index(): RedirectResponse
    {
        $url = $this->urlGenerator->linkToRoute(Application::APP_ID.".main.uebersicht");

        return new RedirectResponse($url);
    }


    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function investmentAktien(): TemplateResponse
    {
        return $this->investment(3);
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function investmentDevisen(): TemplateResponse
    {
        return $this->investment(2);
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function investmentETFs(): TemplateResponse
    {
        return $this->investment(4);
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function investmentRohstoffe(): TemplateResponse
    {
        return $this->investment(1);
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    private function investment(int $type): TemplateResponse
    {
        $investmentResponse = $this->investmentsService->getInvestmentsByTypeId($type);

        $parameters = [
            "data" => json_encode($investmentResponse->data),
            "investmentIncludedModel" => $investmentResponse->investmentIncludedModel,
            "type" => $type
        ];

        return new TemplateResponse(Application::APP_ID, "investment", $parameters);
    }


    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function uebersicht(): TemplateResponse
    {
        $aktien = $this->investmentsService->getInvestmentsByTypeId(3);
        $devisen = $this->investmentsService->getInvestmentsByTypeId(2);
        $etfs = $this->investmentsService->getInvestmentsByTypeId(4);
        $rohstoffe = $this->investmentsService->getInvestmentsByTypeId(1);

        $investmentsDevelopment = $this->investmentsDevelopmentService->getInvestmentsDevelopment();

        $parameters = [
            "aktien" => json_encode($aktien->investmentIncludedModel),
            "devisen" => json_encode($devisen->investmentIncludedModel),
            "etfs" => json_encode($etfs->investmentIncludedModel),
            "rohstoffe" => json_encode($rohstoffe->investmentIncludedModel),

            "investmentsDevelopmentModelJson" => json_encode($investmentsDevelopment)
        ];

        return new TemplateResponse(Application::APP_ID, "uebersicht", $parameters);
    }
}