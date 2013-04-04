<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /index
 */
class BlogIndexResource extends BaseBlogResource
{
	const BLOG_POSTS_PER_PAGE = 3;
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		$subdomain = $this->container['request.subdomain'];
		
		$blog = $this->container['service.blog']->getBySubdomain($subdomain);

		$page = isset($_GET['Page'])
				? intval($_GET['Page'])
				: 1;
		$offset = ($page - 1) * BlogIndexResource::BLOG_POSTS_PER_PAGE;
		$postCount = $this->container['service.blog.post']->getPostCount($subdomain);
		
		$pages = array(
				'Current' => $page
		);
		if($postCount > ($offset + BlogIndexResource::BLOG_POSTS_PER_PAGE))
		{
			$pages['Older'] = ($page + 1);
		}
		if($page > 1)
		{
			$pages['Newer'] = ($page - 1);
		}
		$blog['Pages'] = $pages;
		
		$blog['BlogPosts'] = $this->container['service.blog.post']->getPosts(
				$subdomain,
				BlogIndexResource::BLOG_POSTS_PER_PAGE,
				$offset);
		
		return $this->renderMustacheHeaderFooter('Blog/Index',
				$blog);
	}
}
?>