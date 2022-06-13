<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\TagListComponent;

#[OModuleAction(
	url: '/get-tags',
	filter: 'login',
	services: 'web',
	components: 'api/tag_list'
)]
class getTagsAction extends OAction {
	/**
	 * Función para obtener la lista completa de tags
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$tag_list_component = new TagListComponent(['list' => [], 'extra' => 'nourlencode']);

		if ($status=='ok') {
			$list = $this->web_service->getTagList();
			$tag_list_component = new TagListComponent(['list' => $list, 'extra' => 'nourlencode']);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $tag_list_component);
	}
}