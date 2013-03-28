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
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);

		$blog['BlogPost'] = array('NotEmpty' => TRUE);
		$blog['SaveMethod'] = 'POST';
		
		return $this->renderMustacheHeaderFooter('Blog/Admin/Post/Edit',
				$blog);
	}
	
	/**
	 * @method post
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function save()
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
		
		$postID = $this->container['service.blog.post']->create(
				$blog['Subdomain'],
				$_POST['Title'],
				$_POST['Content']
		);
		
		$post = $this->container['service.blog.post']->getPost(
				$blog['Subdomain'],
				$postID);
		
		throw new \Twestival\RedirectException($post['BlogPostPermalinkUri']);
	}
}
?>