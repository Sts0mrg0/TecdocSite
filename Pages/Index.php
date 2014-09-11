<?php
namespace NS\TecDocSite\Pages;

use NS\TecDocSite\Common\TecDocApiClient;
use NS\TecDocSite\Common\TecDocApiConfig;
use NS\TecDocSite\Common\View;
use NS\TecDocSite\Interfaces\PageInterface;

/**
 * Главная страница. Выводится по умолчанию.
 */
class Index implements PageInterface
{

	/**
	 * Возвращает html страницы.
	 *
	 * @return string
	 */
	public function getHtml() {
		$restClient = new TecDocApiClient(array(TecDocApiConfig::HOST));
		$carType = isset($_GET['carType']) ? $_GET['carType'] : 0;
		$selectedLetter = !empty($_GET['letter']) ? $_GET['letter'] : '';
		$manufacturers = $restClient->getManufacturers($carType);
		$manufacturersTemplateData = array();
		foreach ($manufacturers as $oneManufacturer) {
			if (isset($oneManufacturer->name)) {
				$firstLetter = substr($oneManufacturer->name, 0, 1);
				$manufacturersTemplateData[$firstLetter][] = $oneManufacturer;
			}
		}
		$contentTemplateData = array(
			'manufacturers'  => $manufacturersTemplateData,
			'carType'        => $carType,
			'selectedLetter' => $selectedLetter
		);
		$content = View::deploy('manufacturers.tpl', $contentTemplateData);
		$templateData = array('content' => $content);
		return View::deploy('index.tpl', $templateData);
	}
}