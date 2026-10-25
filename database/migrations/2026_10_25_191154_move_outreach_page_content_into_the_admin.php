<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * The outreach page used to be hard-coded in its Blade template. This copies that
 * content (and its photos) into the page record so it can be edited in the admin.
 * Pages that already have content are left alone.
 */
return new class extends Migration
{
    /**
     * Photos that lived in public/uploads/outreach, in slider order, keyed by their new storage path.
     *
     * @var array<string, string>
     */
    private array $photos = [
        'outreach/img-1157.jpg' => 'IMG_1157.jpg',
        'outreach/img-1199.jpg' => 'IMG_1199.jpg',
        'outreach/2015-07-08.jpg' => '2015-07-08 16.58.54.jpg',
        'outreach/2015-06-10.jpg' => '2015-06-10 12.02.25.jpg',
        'outreach/school-visit.jpg' => 'photo 2.jpg',
        'outreach/inspiring-visit.jpg' => 'Inspiring Visit.JPG',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'outreach')->first();

        if (! $page || ! empty(json_decode($page->content ?? '', true))) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'content' => json_encode([
                'intro' => 'The drive to explore the space around us and to try to understand it is one of the most beautiful and fundamental in human nature. I am thrilled to have the privilege to help operate the Mars rovers, to explore terrains that no humans have ever seen, and then to begin to understand a little bit more about how our planet and solar system formed and evolved to become the places they are today.',
                'contact_callout' => 'I love getting the opportunity to share this work, and especially the images and stories captured by our rovers on Mars, with students and anyone interested in exploration. Please contact me at [outreach@kirstensiebach.com](mailto:outreach@kirstensiebach.com) if you and your group are interested having me speak!',
                'intro_image' => $this->copyPhoto('IMG_4975.jpg', 'outreach/img-4975.jpg'),
                'videos' => [
                    ['title' => 'The Story of Gale Crater on Mars from the NASA Mars Curiosity Rover', 'youtube_url' => 'https://www.youtube.com/watch?v=iMt7azVQsCs', 'description' => null],
                    ['title' => 'SBU Postdoc Spotlight 2017', 'youtube_url' => 'https://www.youtube.com/watch?v=nQeJ945O-_g', 'description' => null],
                    ['title' => 'Houston Geological Society', 'youtube_url' => 'https://www.youtube.com/watch?v=NfpzGW2t_KU', 'description' => null],
                ],
                'photos' => array_values(array_filter(array_map(
                    fn (string $original, string $path): ?string => $this->copyPhoto($original, $path),
                    $this->photos,
                    array_keys($this->photos),
                ))),
                'sections' => $this->sections(),
            ]),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }

    private function copyPhoto(string $original, string $path): ?string
    {
        $source = public_path('uploads/outreach/'.$original);

        if (! is_file($source)) {
            return null;
        }

        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents($source));
        }

        return $path;
    }

    /**
     * @return array<int, array{heading: string, entries: array<int, array{date: ?string, description: string, link: ?string}>}>
     */
    private function sections(): array
    {
        return [
            [
                'heading' => 'Recent Keynote Talks',
                'entries' => [
                    ['date' => '4/11/2019', 'description' => 'West Texas Geological Foundation Annual Luncheon', 'link' => null],
                    ['date' => '3/21/2019', 'description' => 'Dinner Keynote at Rice IRESS Symposium', 'link' => null],
                    ['date' => '3/2/2019', 'description' => 'Rice Visual Communication Symposium', 'link' => null],
                    ['date' => '11/13/2018', 'description' => 'miniGeology Radio Show', 'link' => 'https://youtu.be/YOrarBBx9C0'],
                    ['date' => '10/23/2018', 'description' => 'American Chemical Society Program in a Box "Voyage to Mars: Red Planet Chemistry"', 'link' => 'https://youtu.be/iMt7azVQsCs'],
                    ['date' => '10/22/2018', 'description' => 'Society of Rice University Women', 'link' => null],
                    ['date' => '10/2/2018', 'description' => 'Rice Science Cafe', 'link' => null],
                    ['date' => '9/20/2018', 'description' => 'Four Corners Geological Society', 'link' => 'https://fourcornersgeologicalsociety.org/event/mars-geologic-observations-from-curiosity-rover/'],
                    ['date' => '9/6/2018', 'description' => 'Lunar and Planetary Institute Cosmic Explorations Series', 'link' => 'https://www.lpi.usra.edu/education/cosmic-explorations/'],
                    ['date' => '8/6/2018', 'description' => 'New Orleans Geological Society', 'link' => null],
                    ['date' => '7/25/2018', 'description' => 'URTeC Conference, Houston Convention Center', 'link' => 'http://urtec.org/2018/Events-and-Networking/Topical-Breakfasts'],
                    ['date' => '6/26/2018', 'description' => 'iPoLS Annual Meeting 2018', 'link' => 'https://pols.rice.edu/sites/g/files/bxs241/f/iPoLS%20Annual%20Meeting%202018%20Program%20%26%20Schedule%20FINALPM.pdf'],
                    ['date' => '5/24/2018', 'description' => 'Southwest Research Institute San Antonio, TX', 'link' => null],
                    ['date' => '5/19/2018', 'description' => 'TEF Istanbul STEM Conference', 'link' => 'https://www.en.tef.istanbul/'],
                    ['date' => '4/21/2018', 'description' => 'Sally Ride Rice STEM Festival for Middle School Girls', 'link' => null],
                    ['date' => '4/12/2018', 'description' => 'Brown University Department of Earth, Environmental, and Planetary Science', 'link' => null],
                    ['date' => '3/5/2018', 'description' => 'Houston Geological Society', 'link' => 'https://www.youtube.com/watch?v=NfpzGW2t_KU'],
                    ['date' => '2/14/2018', 'description' => 'NASA Community College Aerospace Scholars (NCAS) Group at NASA Johnson Space Center, Houston, TX', 'link' => null],
                ],
            ],
            [
                'heading' => 'Additional Talks',
                'entries' => [
                    ['date' => '10/29/2018', 'description' => 'Guest Speaker on "The Space Show"', 'link' => 'https://www.thespaceshow.com/show/29-oct-2018/broadcast-3215-dr.-kirsten-siebach'],
                    ['date' => '12/9/2017', 'description' => 'Mission to Mars STEM Workshop for K-12 teachers, Rice University, Houston, TX', 'link' => null],
                    ['date' => '12/6/2017', 'description' => 'The Greens at Half Hollow, Melville, NY', 'link' => null],
                    ['date' => '11/30/2017', 'description' => 'Gulf Coast Exploreum Museum, Mobile, AL', 'link' => 'https://goo.gl/GwV3Lz'],
                    ['date' => '11/16/2017', 'description' => 'Postdoc Research Spotlight Event, Stony Brook, NY', 'link' => 'https://www.youtube.com/watch?v=nQeJ945O-_g'],
                    ['date' => '11/9/2017', 'description' => 'Mohammed Bin Rashid Space Center, Dubai, UAE', 'link' => null],
                    ['date' => '11/8/2017', 'description' => 'GEMS Modern School, Dubai, UAE', 'link' => null],
                    ['date' => '11/5/2017', 'description' => 'Amateur Observers’ Society of NY', 'link' => 'http://www.aosny.org/'],
                    ['date' => '10/27/2017', 'description' => 'Geology Open Night at Stony Brook University', 'link' => 'http://www.geo.sunysb.edu/openight/#siebach'],
                    ['date' => '7/5/2017', 'description' => 'Astronomical Society of Long Island', 'link' => 'http://www.asliclub.org/asliclub.org/NEW_ASLI_Home.html'],
                    ['date' => '5/11/2017', 'description' => 'Caltech Geology Division 90th Anniversary Event', 'link' => 'http://web.gps.caltech.edu/GPS90th/index.html'],
                    ['date' => '3/13/2017', 'description' => 'Guest Lecturer for NASA Endeavor Exploring Mars Class', 'link' => null],
                    ['date' => '7/26/2016', 'description' => 'Student Airborne Research Program NASA Internship, Irvine, CA, “Mars Exploration”', 'link' => null],
                    ['date' => '4/15/2016', 'description' => '3 Minute Thesis Competition Finalist, Caltech, Pasadena, CA " Decoding the Rock Record: Revealing Ancient Environments on Mars"', 'link' => 'https://vimeo.com/159468342/8e89b938db'],
                    ['date' => '3/13/2016', 'description' => 'LA Valley College Astronomy Club, Valley Glen, CA "Exploring Mars with the Curiosity Rover"', 'link' => 'http://lavcastrogroup.org/'],
                    ['date' => '3/10/2016', 'description' => 'Guest Lecture in Online NASA K-12 teacher training course, “Exploring Mars: A New Twist on Science (or Math)”', 'link' => 'http://www.us-satellite.net/endeavor/course.cfm?courseid=25'],
                    ['date' => '3/3/2016', 'description' => 'Keynote Speaker at Global Education Supplies & Solutions (GESS) Conference in Dubai, UAE', 'link' => 'http://www.gessdubai.com/speakers/kirsten-siebach'],
                    ['date' => '3/2/2016', 'description' => 'GEMs Wellington International School, Dubai, UAE, "Exploring Mars with the Curiosity Rover"', 'link' => null],
                    ['date' => '3/1/2016', 'description' => 'Keynote Talk at LEGO Education VIP Evening associated with GESS Conference in Dubai, UAE, "The Process of Discovery, on Mars and in the Classroom"', 'link' => null],
                    ['date' => '1/25/2016', 'description' => 'Rancho Santa Gertrudes Elementary School Assembly, Whittier, CA “Road Trips on Mars”', 'link' => null],
                    ['date' => '1/10/2016', 'description' => 'Science Sunday Public Lecture at Caltech, Pasadena, CA, “Road Trips on Mars”', 'link' => null],
                    ['date' => '9/16/2015', 'description' => 'Keynote for LEGO Education Conference, Singapore, Singapore, “Exploring Mars and the Process of Discovery”', 'link' => null],
                    ['date' => '9/14/2015', 'description' => 'Rectors Tea at Yale-NUS, Singapore, Singapore, “Mars Exploration”', 'link' => null],
                    ['date' => '2/13/2015', 'description' => 'Reel Friday Public Lecture at Caltech, Pasadena, CA, “Ultimate Mars Challenge”', 'link' => null],
                    ['date' => '11/7/2014', 'description' => 'Siemens Science Competition Regional Finals, Pasadena, CA, “Mars Exploration”', 'link' => null],
                    ['date' => '10/25/2014', 'description' => 'Washington University in St. Louis Alumni Events, Pasadena, CA, “Exploring Mars with the Curiosity Rover” panel member', 'link' => null],
                    ['date' => '2/21/2014', 'description' => 'Reel Friday Public Lecture at Caltech, Pasadena, CA, “Trial by Fire: How Collisions and Catastrophes enabled Life on Earth”', 'link' => null],
                    ['date' => '1/11/2013', 'description' => 'Reel Friday Public Lecture at Caltech, Pasadena, CA, “Rock my World: The Power of Volcanoes”', 'link' => null],
                    ['date' => 'Various', 'description' => 'Over 40 additional outreach talks to public and school groups reaching over 1500 people', 'link' => null],
                ],
            ],
            [
                'heading' => 'Teaching Experience',
                'entries' => [
                    ['date' => null, 'description' => 'Developed and Taught Planetary Surface Processes Rice University SP 2019', 'link' => null],
                    ['date' => null, 'description' => 'Instructor of Record for NASA Endeavor Program "Exploring Mars" class, Spring 2018', 'link' => 'http://www.us-satellite.net/endeavor/'],
                    ['date' => null, 'description' => 'Instructor of Record for NASA Endeavor Program "Eyes on Earth" class, Summer 2017', 'link' => 'http://www.us-satellite.net/endeavor/'],
                    ['date' => null, 'description' => 'Geology instructor for a 2-week American Frontier History Field Trip in Colorado, Wyoming, Montana, and South Dakota with a group of home-schooled students through Schooling for Life.', 'link' => 'http://www.schoolingforlife.org'],
                    ['date' => null, 'description' => 'Developed and taught an 8-week after-school class, “Martian Explorations”, for gifted elementary school students through the Institute for Educational Advancement in Pasadena, CA in Spring 2014, Fall 2014, and Winter 2015.', 'link' => 'http://educationaladvancement.org'],
                    ['date' => null, 'description' => 'Field and Teaching Assistant Ireland Geological Field Course, James Madison University, 2 weeks, Summer 2015', 'link' => null],
                    ['date' => null, 'description' => 'Teaching Assistant Remote Sensing, Caltech Graduate Course, Spring 2013 and Spring 2015', 'link' => null],
                    ['date' => null, 'description' => 'Teaching and Lab Assistant Intro to Geology, Caltech Undergraduate Course, Fall 2014', 'link' => null],
                    ['date' => null, 'description' => 'Teaching and Lab Assistant Land Dynamics, Washington University in St. Louis, Spring 2008, Spring 2009, and Spring 2010', 'link' => null],
                ],
            ],
            [
                'heading' => 'Additional outreach experience',
                'entries' => [
                    ['date' => null, 'description' => 'Volunteer expert at 8 NASA booth events', 'link' => null],
                    ['date' => null, 'description' => 'Invited Lectures for NASA Student Airborne Research Program Summer Interns 2012, 2013, 2014, 2015, 2016', 'link' => null],
                    ['date' => null, 'description' => 'Developed and ran a one-day seminar for gifted elementary students on William Smith and Geologic Mapping at the Huntington Gardens in Pasadena, November 2014', 'link' => null],
                    ['date' => null, 'description' => 'Judge for American Geophysical Institute Award at Intel International Science Fair, 2014', 'link' => null],
                    ['date' => null, 'description' => 'Expert Reader for National Geographic Kids book, “Mars”, Fall 2014', 'link' => null],
                    ['date' => null, 'description' => 'Organized and staffed Caltech “Exploration Station” booth at the 2013 AGU conference meeting', 'link' => null],
                    ['date' => null, 'description' => 'High School Teacher Training Talk on Applications of Spectroscopy, DNP Conference 2012', 'link' => null],
                    ['date' => null, 'description' => 'Caltech Classroom Connection Volunteer (2011-12); aid with school farm soil testing', 'link' => null],
                    ['date' => null, 'description' => 'Invited Lecture at Central Methodist University Math and Science Competition, 2008', 'link' => null],
                ],
            ],
        ];
    }
};
