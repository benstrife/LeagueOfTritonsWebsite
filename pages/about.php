<?php

$officer[1] = array(
  "position" => "President",
  "image" => "kevin",
  "rank" => "diamond",
  "name" => "Kevin He",
  "summoner" => "SoKorean",
  "bio" => "I'm currently a Sophomore at UCSD, majoring in Computer Science, and I've been 
      playing League of Legends since 2009. I'm a gamer at heart, and if I'm not on League 
      I spend most of my time trying to clean out my Steam Library, but I also enjoy food, 
      Taekwondo, and wasting time on my phone. As your President for this school year, it's
      my job to make sure every League of Tritons member has a positive experience, so feel 
      free to talk to me in-game or at school if you have questions, concerns, or comments.",
  "lolking" => "20284134"
);

$officer[2] = array(
  "position" => "Vice-President",
  "image" => "andrew",
  "rank" => "diamond",
  "name" => "Andrew Char",
  "summoner" => "Cry&oslashph&oslashenix",
  "bio" => "Hi, I'm Andrew Char! After avidly playing League of Legends for over two years, 
      I came to UCSD and found League of Tritons, a community of kind and cheery League players.
      Over the past years I've enjoyed seeing changes in League, from changes in individual champions 
      and items to map overhauls, the introduction of great new game modes, and the growth of eSports!
      I love playing mobile champions, such as Ezreal, Nidalee and Quinn, and have recently developed 
      an obsession with Homeguard mobility boots.  For the most part, games I play outside of League include:
      osu!, Minecraft, and Tetris Battle, but I enjoy trying out new games! I'm eagerly awaiting the upcoming
      school year, not only as a 2nd year Comp Sci major and a fellow League of Legends player, but 
      also as the League of Tritons Vice-President.",
  "lolking" => "24527597"
);

$officer[3] = array(
  "position" => "Team Manager",
  "image" => "jeff",
  "rank" => "diamond",
  "name" => "Jeff Hoang",
  "summoner" => "Gray",
  "bio" => "I have been a member of the League of Tritons since 2012. Prior to my acceptance into UCSD, my gaming history consisted mainly of Maplestory and DotA during my middle and high school years. As e-Sports began to grow, I became a beta tester for games that were inspired by DotA, such as Heroes of Newerth and League of Legends. For a short while, I was a GM for Heroes of Newerth until I decided to go back to League of Legends. What brought me back to League of Legends was the amount of money they pumped into the competitive e-Sports scene. As the competitive team manager for UCSD, I plan to help train teams in order to compete at a competitive level.",
  "lolking" => "19781"
);

$officer[4] = array(
  "position" => "Event Manager",
  "image" => "michael",
  "rank" => "diamond",
  "name" => "Michael Chin",
  "summoner" => "iTrinitron",
  "bio" => "Hello! My name is Michael. I am a second year computer science major at Muir College.  Before I started
      playing League of Legends, I was a huge fan of Starcraft and Warcraft III's custom game community.  My favorite
      Starcraft map was Miles Laser TAG, while my favorite Warlock III map was Warlock.  I also enjoyed making clans on
      Battle.net!  If anyone played Warlock or Island Defense, I was the leader of Clan AWz (Amped Warlockz) and LoR (Last Resort),
      both of which controlled those two games respectively on US-West.  As for my role as an officer, I am in charge of 
      hosting events, such as GBL's and tournaments.  Additionally, I also serve as the webmaster and designer of this website 
      (so if you have questions contact me!).  It's taking a while to finish because it's a one-man project, but hopefully one day,
      it'll be a functioning work chock full of information.",
  "lolking" => "21349590"
);

$officer[5] = array(
  "position" => "Public Relations",
  "image" => "raymond",
  "rank" => "platinum",
  "name" => "Raymond Liu",
  "summoner" => "Proto55",
  "bio" => "I have been a League of Triton member for a little less than a year.
      I played Starcraft: Brood War before I came to college. Once getting into college,
      I started to play League of Legends because it was a great conversational point; 
      But that doesn't mean I still dont enjoy the occasional splurge of GTA and Saints
      Row every so often. I also play games such as Fall Out, Planetside 2, and Battlefield 3.
      I'm always open to try new games and activities, but as a second year CS major, I don't
      get as much time to play as I'd like. As League of Tritons Public Relations Officer, I 
      look forward to making connections with the community of TeSPA and getting to know new people!",
  "lolking" => "21256755"
);

$officer[6] = array(
  "position" => "Treasurer",
  "image" => "sam",
  "rank" => "diamond",
  "name" => "Sam Ko",
  "summoner" => "Cinnamon Bagels",
  "bio" => "",
  "lolking" => "21925330"
);
?>

<div id="head_box">
    <div id="about_us">
            <div id="title">About Us</div>
            The League of Tritons was founded by <span>Christopher Zhu</span> in 2011 as a UCSD on-campus organization. 
            The club began as a group of League of Legends players hoping to form a player base at UCSD large
            enough to compete with the popular Starcraft 2 club. With the recent surge in League of Legends popularity, 
            however, players from all over campus came together to bolster the organization's ranks. From a 
            small group of about 30 members, the League of Tritons has expanded to over 700 members in the last two years.
            <br/><br/>
            The first club events initially consisted of just the officers and their friends, which totaled about 10 people. 
            Thanks to the hard work and dedication of many of the original members, including <span>Michelle Nguyen</span>, <span>Melissa Sumarli</span>, 
            <span>Michelle Lin</span>, <span>Nick Wang</span>, and <span>Benjamin Young</span>, the club now hosts wide scale intra-school and inter-collegiate events 
            that notoriously exceed UCSD room limits. As the club grew from a small gaming gathering to a large organization, 
            the League of Tritons began hosting social events like Beach Days, Karaoke Nights, and KBBQ Parties to encourage 
            the massive influx of members to bond and hang out with people that share the same passions. With the introduction 
            of the Collegiate Star League, or CSL, in 2011, we bannered around our talented League of Legends competitive players 
            as a pedestal of skill level that all members could strive to achieve.
            <br/><br/>
            Today, the League of Tritons is one of the largest and most active on-campus organizations both at UCSD and in California. 
            As a collective of gamers at heart, we strive to encourage unity between our members and within the community as a whole by
            providing a competitive, social, and positive environment for all players.
        </div>
</div>
<div id="about">
    <div class="box">
        <div id="officer_list">
            <?php 
            for($i = 1; $i <= 6; ++$i) {
                echo '<div class="officer">';

                echo '<div class="picture">
                    <img class="profile_img" src="css/img/officer/' . $officer[$i]["image"] . '.jpg"/>
                        <img class="elo" src="css/img/' . $officer[$i]["rank"] . '_border.png"/>
                </div>';
                
                echo '<div class="info">';
                echo '<div class="position">' . $officer[$i]["name"] . '</div>';
                echo '<div class="name">' . $officer[$i]["position"] . ' - <i><a href="http://www.lolking.net/summoner/na/' . $officer[$i]["lolking"] . '">' . $officer[$i]["summoner"]  . '</i></a></div>';
                echo '<div class="bio">' . $officer[$i]["bio"] . '</div>';
                echo '</div>';
                    echo '<div class="clear"></div>
            </div>';
                
            }
          ?>
        </div>
    </div>
</div>