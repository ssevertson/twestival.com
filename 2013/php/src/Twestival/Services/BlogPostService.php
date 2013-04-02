<?php namespace Twestival\Services;

use Twestival\DAOs\BlogsDAO;

class BlogPostService extends BaseService
{
	function getPosts($subdomain, $count, $offset)
	{
		$posts = $this->container['dao.blog.posts']->items($subdomain, $count, $offset);
		$this->addUrisToBlogPosts($posts);
		return $posts;
	}
	function getPost($subdomain, $postID)
	{
		$post = $this->container['dao.blog.posts']->get($subdomain, $postID);
		$this->addUrisToBlogPost($post);
		return $post;
	}
	function getPostCount($subdomain)
	{
		return $this->container['dao.blog.posts']->count($subdomain);
	}
	
	function update($subdomain, $postID, $title, $content)
	{
		return $this->container['dao.blog.posts']->update($subdomain, $postID, $title, $content);
	}
	function create($subdomain, $title, $content)
	{
		return $this->container['dao.blog.posts']->create($subdomain, $title, $content);
	}
	function delete($subdomain, $postID)
	{
		return $this->container['dao.blog.posts']->delete($subdomain, $postID);
	}

	private function addUrisToBlogPosts(&$posts)
	{
		foreach($posts as &$post)
		{
			$this->addUrisToBlogPost($post);
		}
	}
	private function addUrisToBlogPost(&$post)
	{
		$post['BlogPostUri'] = 'http://'
				. $post['BlogSubdomain']
				. '.'
				. $this->container['request.domain']
				. $this->container['baseUri']
				. '/post/'
				. $post['PostID'];
		
		$cleanTitle = $this->toCleanUri($post['Title']);
		
		$post['BlogPostPermalinkUri'] = $post['BlogPostUri']
				. '/'
				. $cleanTitle;
		
		$legacy = ($post['Created'] < '2013-01-01');
		if($legacy)
		{
			$post['BlogPostShareUri'] = 'http://'
					. $post['BlogSubdomain']
					. '.'
					. $this->container['request.domain']
					. '/blog-entry/'
					. $post['PostID']
					. '/'
					. $cleanTitle
					. '.html';
		}
		else
		{
			$post['BlogPostShareUri'] = $post['BlogPostPermalinkUri'];
		}
	}
	
	private function toCleanUri($title,  $delimiter='-')
	{
		$clean = preg_replace(array('/Ä/', '/Ö/', '/Ü/', '/ä/', '/ö/', '/ü/'), array('Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue'), $title);
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
		$clean = preg_replace('/[^a-zA-Z0-9\/_|+ -]/', '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace('/[\/_|+ -]+/', $delimiter, $clean);
		return $clean;
	}
}
?>