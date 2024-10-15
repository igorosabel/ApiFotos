<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetTags;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Component\Model\TagList\TagListComponent;

class GetTagsComponent extends OComponent {
	private ?WebService $ws = null;

	public string $status = 'ok';
	public ?TagListComponent $list = null;

	public function __construct() {
    parent::__construct();
		$this->ws = inject(WebService::class);
		$this->list = new TagListComponent();
	}

	/**
	 * Función para obtener la lista completa de tags
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		if ($this->status === 'ok') {
			$tag_list = $this->ws->getTagList();
			$this->list->list = $tag_list;
		}
	}
}
