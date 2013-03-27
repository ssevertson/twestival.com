<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /post
 */
class BlogAdminPostCreateResource extends BaseBlogResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function showEditor()
	{
		$subdomain = $this->container['request.subdomain'];
		
		$data = $this->container['service.blog']->getBySubdomain($subdomain);

		$data['BlogPost'] = array('NotEmpty' => TRUE);
		$data['SaveMethod'] = 'POST';
		
		return $this->renderMustacheHeaderFooter('Blog/Admin/Post/Edit',
				$data);
	}
	
	/**
	 * @method post
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function save()
	{
		$subdomain = $this->container['request.subdomain'];
		
		$postID = $this->container['service.blog.post']->create(
				$subdomain,
				$_POST['Title'],
				$_POST['Content']
		);
		
		$post = $this->container['service.blog.post']->getPost(
				$subdomain,
				$postID);
		
		throw new \Twestival\RedirectException($post['BlogPostPermalinkUri']);
	}
}
?>