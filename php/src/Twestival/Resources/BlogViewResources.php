<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /post/{postID}/{title}
 * @uri /blog-entry/{postID}/{title}
 */
class BlogViewResource extends BaseBlogResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html($postID)
	{
		$subdomain = $this->container['request.subdomain'];
		
		$data = $this->container['service.blog']->getBySubdomain($subdomain);

		$data['BlogPost'] = $this->container['service.blog.post']->getPost(
				$subdomain,
				intval($postID));
		
		return $this->renderMustacheHeaderFooter('Blog/View',
				$data);
	}
}
?>