<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /post/{postID}
 */
class BlogAdminPostEditResource extends BaseBlogResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function showEditor($postID)
	{
		$subdomain = $this->container['request.subdomain'];
		$postID = intval($postID);
		
		$data = $this->container['service.blog']->getBySubdomain($subdomain);

		$data['BlogPost'] = $this->container['service.blog.post']->getPost(
				$subdomain,
				$postID);
		$data['SaveMethod'] = 'PUT';
		
		return $this->renderMustacheHeaderFooter('Blog/Admin/Post/Edit',
				$data);
	}
	
	/**
	 * @method put
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function save($postID)
	{
		$subdomain = $this->container['request.subdomain'];
		$postID = intval($postID);
		
		$this->container['service.blog.post']->update(
				$subdomain,
				$postID,
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