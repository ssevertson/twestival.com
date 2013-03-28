<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /post/{postID}
 */
class BlogAdminPostItemResource extends BaseBlogResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function showEditor($postID)
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
		
		$blog['BlogPost'] = $this->container['service.blog.post']->getPost(
				$blog['Subdomain'],
				intval($postID));
		$blog['SaveMethod'] = 'PUT';
		
		return $this->renderMustacheHeaderFooter('Blog/Admin/Post/Edit', $blog);
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

	/**
	 * @method delete
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function delete($postID)
	{
		$subdomain = $this->container['request.subdomain'];
		$postID = intval($postID);
	
		$this->container['service.blog.post']->delete(
				$subdomain,
				$postID
		);
	
		throw new \Twestival\RedirectException('/');
	}
}
?>