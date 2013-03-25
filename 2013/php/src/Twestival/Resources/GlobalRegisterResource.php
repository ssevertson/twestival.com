<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /register
 */
class GlobalRegisterResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function showForm()
	{
		return $this->renderMustacheHeaderFooter('Global/Register');
	}
	
	/**
	 * @method post
	 * @provides text/html
	 */
	function saveRegistration()
	{
		$this->container['service.registration']->save(
			$_POST['ReRegistration'] === 'true',
			$_POST['Name'],
			$_POST['TwitterName'],
			$_POST['EmailAddress'],
			$_POST['City'],
			$_POST['StateProvince'],
			$_POST['Country'],
			$_POST['PreferredTwestivalName'],
			$_POST['CharityDescription'],
			$_POST['Comment']
		);
		
		throw new \Twestival\RedirectException('/thanks/register');
	}
}
?>