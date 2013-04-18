<?php namespace Twestival\Resources;
/**
 * @namespace global
 * @uri /team
 */
class GlobalTeamResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Team', array(
				'Groups' => array(
						array(
								'Name' => 'Founder & Advisor',
								'Members' => array(
										array(
												'Lead' => TRUE,
												'Name' => 'Amanda Rose',
												'Location' => 'Canada',
												'TwitterName' => 'amanda',
												'Tweet' => 'It\'s inspiring how many volunteers around the world have given their time and talent to host a Twestival in their community since 2009.'
										)
								)
						),
						array(
								'Name' => 'Vetting & Approvals',
								'Members' => array(
										array(
												'Lead' => TRUE,
												'Name' => 'Wendy Lou',
												'Location' => 'US',
												'TwitterName' => 'itswendylou',
												'Tweet' => 'Twestival is community & giving for the sake of community & giving. Seeing people, worldwide, rally for good causes gives me warm fuzzies.'
										),
										array(
												'Name' => 'Sara Needham',
												'Location' => 'US',
												'TwitterName' => 'SaraNeedham',
												'Tweet' => 'We\'re powerful when we\'re connected. That\'s Twestival. Grateful to be part of a worldwide community causing positive change.'
										),
										array(
												'Name' => 'Marieme Jamme',
												'Location' => 'UK',
												'TwitterName' => 'mjamme',
												'Tweet' => 'Thrilled to be leading Twestival Africa. Not about aid but having fun, taking action and making an enormous difference.'
										),
										array(
												'Name' => 'Jad Hindy',
												'Location' => 'UAE',
												'TwitterName' => 'jadhindy',
												'Tweet' => 'Twestival turns your eagerness to help & give into a reality, a global reality!'
										),
										array(
												'Name' => 'Shauna Causey',
												'Location' => 'US',
												'TwitterName' => 'shaunacausey',
												'Tweet' => 'Twestival is creating ripples of change across the world. It\'s the heart of how technology can enable a digital connected community.'
										),
										array(
												'Name' => 'Vaijayanthi KM (VJ)',
												'Location' => 'India',
												'TwitterName' => 'adropofwisdom',
												'Tweet' => 'Excited to be part of Twestival again! Let\'s make all the difference we can!'
										),
										array(
												'Name' => 'Erika Fonseca',
												'Location' => 'Mexico',
												'TwitterName' => 'erfonseca',
												'Tweet' => 'It\'s great to be part of a community that tries to do great little things, to have a better world!'
										),
								)
						),
						array(
								'Name' => 'Orientation',
								'Members' => array(
										array(
												'Lead' => TRUE,
												'Name' => 'Christina Lor',
												'Location' => 'US',
												'TwitterName' => 'skimtheocean',
												'Tweet' => 'Twestival continues to make strides in compassion, service + ACTION! Pay it forward or take a little back, it\'s out there to give + receive!'
										),
										array(
												'Name' => 'Ian Scott Haisley',
												'Location' => 'US',
												'TwitterName' => 'ianscotthaisley',
												'Tweet' => 'I\'m excited to be a part of the @twestival Orientation team! This is going to be the best one yet. Ask me how you can get involved!'
										),
										array(
												'Name' => 'Mark Jensen',
												'Location' => 'Denmark',
												'TwitterName' => 'marks',
												'Tweet' => 'Ask! It\'s the one, simple thing that will benefit you the most. Sponsors, acts, previous organizers—just ask, and it\'ll all come together.'
										),
										array(
												'Name' => 'Andy Keetch',
												'Location' => 'UK',
												'TwitterName' => 'andykeetch',
												'Tweet' => 'I\'ve loved twestival from the very start: twitter, beers and making good things happen FTW Join in, get involved!'
										),
										array(
												'Name' => 'Chris Lee',
												'Location' => 'US',
												'TwitterName' => 'chrislee',
												'Tweet' => 'So excited to help @Twestival in a new capacity. Love our @TwestivalPhx event but also looking forward to help new organizers!'
										),
										array(
												'Name' => 'Donna McTaggart',
												'Location' => 'Canada',
												'TwitterName' => 'donnamct',
												'Tweet' => 'Quiet start but huge impact from the beginning. Twestival is an example how common goals can move mountains, drill wells, change lives!'
										),
										array(
												'Name' => 'Francesco Iandola',
												'Location' => 'Italy',
												'TwitterName' => 'francescoi',
												'Tweet' => 'Proud to represent Italy and Milan Twestival in the Global Team and to learn and to teach to use social media for a better world'
										),
										array(
												'Name' => 'Jackie Miao',
												'Location' => 'Malaysia',
												'TwitterName' => 'fooz',
												'Tweet' => 'I love Twestival because you see hope turn into reality if you put all your heart in it! Let\'s change the world for the better together!'
										),
								)
						),
						array(
								'Name' => 'Community',
								'Members' => array(
										array(
												'Lead' => TRUE,
												'Name' => 'Sylwia Presley',
												'Location' => 'UK',
												'TwitterName' => 'presleysylwia',
												'Tweet' => '@presleysylwia loves @Twestival because it brings people together - so join us: meet up, have a coffee together and organise an event too! ;)'
										),
										array(
												'Name' => 'Sylvia Tan',
												'Location' => 'Canada',
												'TwitterName' => 'sylvia_tan',
												'Tweet' => 'Let\'s connect. We\'re all in this together.'
										),
										array(
												'Name' => 'Laila Takeh',
												'Location' => 'UK',
												'TwitterName' => 'spirals',
												'Tweet' => 'Digital geek, charity geek, crochet at the weekends. I know the world can be better if we make it that way'
										),
										array(
												'Name' => 'Damien Austin Walker',
												'Location' => 'UK',
												'TwitterName' => 'b33god',
												'Tweet' => 'Proud Twestival volunteer over the years. Love it and looking forward to see impact of Twestival 2013. Think global act local!'
										),
								)
						),
						array(
								'Name' => 'Editorial',
								'Members' => array(
										array(
												'Lead' => TRUE,
												'Name' => 'Andrew Wahl',
												'Location' => 'Canada',
												'TwitterName' => 'andrewwahl',
												'Tweet' => 'Cranked to share tales of Twestival awesome past and present in words and images. Hoping to inspire, inform and amuse! What\'s your story?'
										),
										array(
												'Name' => 'Jon Cartwright',
												'Location' => 'UK',
												'TwitterName' => 'joncart',
												'Tweet' => 'I\'ve been proud to support Twestival from the beginning. It\'s a great example of how the network effect can get good things done.'
										),
										array(
												'Name' => 'Bashar Alaeddin',
												'Location' => 'Jordan',
												'TwitterName' => 'balaeddin',
												'Tweet' => 'Marhaba! Excited to be part of the global Twestival team in helping bridge cultures together making the whole world a much friendlier place.'
										),
										array(
												'Name' => 'Gretel Truong',
												'Location' => 'US',
												'TwitterName' => 'greteltruong',
												'Tweet' => 'Helping to prove a tweet can create change around the world. Specializing in #multimedia #mobilizing & #connecting'
										),
								)
						),
						array(
								'Name' => 'Communications',
								'Members' => array(
										array(
												'Lead' => TRUE,
												'Name' => 'Stuart Wragg',
												'Location' => 'Australia',
												'TwitterName' => 'stuwragg',
												'Tweet' => 'Excited about the connections, collaboration and change social media enables. Looking forward to seeing what\'s possible in the year ahead.'
										),
										array(
												'Name' => 'Alex Myers',
												'Location' => 'UK',
												'TwitterName' => 'alexmyers',
												'Tweet' => 'Twestival is awesome because it\'s born from a few people thinking \'lets do something good\'. We could all do that more often.'
										),
										array(
												'Name' => 'Laura Ciocia',
												'Location' => 'US',
												'TwitterName' => 'msciocia',
												'Tweet' => 'What if people from across the globe connected around a shared commitment to be the change they wish to see?  Welcome to Twestival.'
										),
										array(
												'Name' => 'Susan McPherson',
												'Location' => 'US',
												'TwitterName' => 'susanmcp1',
												'Tweet' => 'So jazzed to collaborating with, and supporting the amazing tradition! @Twestival you are making the world a better place.'
										),
								)
						),
						array(
								'Name' => 'Fundraising',
								'Members' => array(
										array(
												'Lead' => TRUE,
												'Name' => 'Marc Pitman',
												'Location' => 'US',
												'TwitterName' => 'marcapitman',
												'Tweet' => 'The #1 reason people give money? They are asked! Go out & ask, even if you don\'t feel you have it all together. Asking becomes really fun!'
										),
										array(
												'Name' => 'Alisa Gilbert',
												'Location' => 'US',
												'TwitterName' => 'sulook',
												'Tweet' => 'Be part of the next generation of change and giving with @Twestival. Together we can and will change the world.'
										),
								)
						),
						array(
								'Name' => 'Tech',
								'Members' => array(
										array(
												'Lead' => TRUE,
												'Name' => 'Scott Severtson',
												'Location' => 'US',
												'TwitterName' => 'GeekDadAVL',
												'Tweet' => 'Being a geek is fun. Being a geek for a great cause is awesome!'
										),
										array(
												'Name' => 'Benjamin Wendelboe',
												'Location' => 'Denmark',
												'TwitterName' => 'wendelboe',
												'Tweet' => 'Twou tway tway twi\'m twa tweamer, twut twi\'m twot twe twonly twone, Twi twope twome tway twou\'ll twoin twus, twand twe tworld twill twe twas twone'
										),
										array(
												'Name' => 'Lone Palmus Jensen',
												'Location' => 'Denmark',
												'TwitterName' => 'lpj',
												'Tweet' => 'After having enjoyed a few Twestivals in Copenhagen I did not hesitate when I got the chance to contribute to this awesome cause.'
										),
										array(
												'Name' => 'Lixon Louis',
												'Location' => 'India',
												'TwitterName' => 'lixonic',
												'Tweet' => 'Proud to be a part of a worldwide community which strives for good things.'
										),
								)
						),
						array(
								'Name' => 'Design',
								'Members' => array(
										array(
												'Name' => 'Morgan Rose',
												'Location' => 'Canada',
												'TwitterName' => 'morganlisarose',
												'Tweet' => 'So proud to be a part of it @twestival. Think global. Act local. Start something beautiful.'
										),
										array(
												'Name' => 'Ignacio Garcia',
												'Location' => 'Argentina',
												'TwitterName' => 'ignacio',
												'Tweet' => 'Welcome to Twestival!'
										),
										array(
												'Name' => 'Amina Abdellatif',
												'Location' => 'Tunisia',
												'TwitterName' => 'viagramoniak',
												'Tweet' => 'Giving Twestival an identity worldwide is a nice challenge. It reflects what we do « Think global. Act local.'
										),
										array(
												'Name' => 'Emilia Maj',
												'Location' => 'Poland',
												'TwitterName' => 'emiliamaj',
												'Tweet' => 'I\'m excited to join to the Twestival global movement and strongly encourage you to do so! Guys, let\'s create powerful & helpful community!'
										),
								)
						),
						array(
								'Name' => 'Translations',
								'Members' => array(
										array(
												'Lead' => TRUE,
												'Name' => 'Gaëlle Callnin',
												'Location' => 'US',
												'TwitterName' => 'cofrenchy',
												'Tweet' => 'I\'m thrilled to be part of #Twestival and continue supporting its amazing causes and communities! Language = communication = connection.'
										),
								)
						)
				)
		));
	}
}
?>