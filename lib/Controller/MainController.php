<?php

namespace OCA\Investments\Controller;

use OCA\Investments\AppInfo\Application;
use OCA\Investments\Services\ApiService;
use OCA\Investments\Services\InvestmentsService;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\Http\Client\IClientService;


class MainController extends Controller
{
    private $clientService;
    private $investmentsService;
    private $urlGenerator;


    public function __construct($AppName, IRequest $request, IClientService $clientService, IURLGenerator $urlGenerator)
    {
        parent::__construct($AppName, $request);

        $userFilesService = new UserFilesService(User::USER);

        $this->clientService = $clientService;
        $this->investmentsService = new InvestmentsService($userFilesService);
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
        return $this->investment("Aktie");
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function investmentDevisen(): TemplateResponse
    {
        return $this->investment("Devise");
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function investmentETFs(): TemplateResponse
    {
        return $this->investment("ETF");
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function investmentRohstoffe(): TemplateResponse
    {
        return $this->investment("Rohstoff");
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    private function investment(string $type): TemplateResponse
    {
        $investmentResponse = ApiService::getInvestmentsByType($this->clientService, $type);

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
        $aktien = ApiService::getInvestmentsByType($this->clientService, "Aktie");
        $devisen = ApiService::getInvestmentsByType($this->clientService, "Devise");
        $etfs = ApiService::getInvestmentsByType($this->clientService, "ETF");
        $rohstoffe = ApiService::getInvestmentsByType($this->clientService, "Rohstoff");

        $investmentsDevelopment = $this->investmentsService->getInvestmentsDevelopment();

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