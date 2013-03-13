<?php
/**
 * Generate Atom
 */

class Atom {

	public function __construct() {}

	public function run() {
		NanoIO::writeln('Building Atom');

		$count = 0;

		$atom = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$atom .= "<feed xmlns=\"http://www.w3.org/2005/Atom\">\n";

		$atom .= "\t<title>" . BLOG_NAME . "</title>\n";
		$atom .= "\t<subtitle>" . BLOG_SLOGAN . "</subtitle>\n";
		$atom .= "\t<link href=\"http://" . BLOG_DNS . "/atom.xml\" rel=\"self\" />\n";
		$atom .= "\t<link href=\"http://" . BLOG_DNS . "\" />\n";
		$atom .= "\t<id>urn:uuid:60a76c80-d399-11d9-b91C-0003939e0af6</id>\n";
		$atom .= "\t<updated>" . date(DATE_ATOM) . "</updated>\n";

		foreach(Resource::get('article') as $article_info) {
			$atom .= "\t<entry>\n";
			$atom .= "\t\t<title>{$article_info['title']}</title>\n";
			$atom .= "\t\t<link href=\"http://" . BLOG_DNS . BLOG_PATH ."{$article_info['url']}\" />\n";
			$atom .= "\t\t<link rel=\"alternate\" type=\"text/html\" href=\"http://example.org/2003/12/13/atom03.html\"/>\n";
			$atom .= "\t\t<link rel=\"edit\" href=\"http://example.org/2003/12/13/atom03/edit\"/>\n";
			$atom .= "\t\t<id>urn:uuid:1225c695-cfb8-4ebb-aaaa-80da344efa6a</id>\n";
			$atom .= "\t\t<updated>" . date(DATE_ATOM) . "</updated>\n";
			$atom .= "\t\t<summary>Some text.</summary>\n";
			$atom .= "\t\t<author>\n";
			$atom .= "\t\t\t<name>John Doe</name>\n";
			$atom .= "\t\t\t<email>johndoe@example.com</email>\n";
			$atom .= "\t\t</author>\n";
			$atom .= "\t</entry>\n";

			if ($count++ > 5)
				break;
		}

		$atom .= "</feed>";

		writeTo($atom, PUBLIC_FOLDER . 'atom.xml');
	}
}