<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /post/{postID}
 * @uri /post/{postID}/{title}
 */
class BlogItemResource extends BaseBlogResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html($blogID)
	{
		$subdomain = $this->container['request.subdomain'];
		
		$data = $this->container['service.blog']->getBySubdomain($subdomain);

		$data['BlogPost'] = $this->container['service.blog.post']->getPost(
				$subdomain,
				intval($blogID));
		
		return $this->renderMustacheHeaderFooter('Blog/View',
				$data);
	}
}
?>