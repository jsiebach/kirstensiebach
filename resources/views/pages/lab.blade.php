@extends('master')

@section('page_title')
    {{$page->meta_title}}
@endsection

@section('body')
    @include('partials.page-title')

    <img style="width: 80%; height: auto; margin-left: 10%;" src="/uploads/team/banner-2020.jpg" alt="">
    <br>
    <br>
    <p>Welcome to the Siebach Lab website! We study the history of surface environments and water on Mars and early Earth using the stories recorded in sedimentary rocks. We are particularly interested in understanding the sedimentary cycle on Mars and comparing it to the terrestrial sedimentary cycle in order to better understand the uniqueness of Earth and the ancient habitable environments of Mars. We use imaging, geochemical, and mineralogical data from Martian spacecraft, especially rovers, along with data collected at analogous sites on Earth and visualization techniques to gain understanding of these systems.</p>

    <h2>Lab Team</h2>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <h3>Valerie Payre</h3>
            <h5>vpayre@rice.edu</h5>
            <p>Valerie is a Weiss Postdoctoral Research Fellow in DEEPS, working with Kirsten on martian magmatic and surface processes. She did her Ph.D in geosciences at Université de Lorraine in Nancy, France, and get her bachelor's and master's degree in geochemistry at Ecole Normale Supérieure (ENS) Ulm in Paris, France. She loves fitness, running, dancing, and traveling all around the world, discovering new cultures and new geological histories.</p>
            <p>Valerie's work in Kirsten's lab is mainly focused on investigating Mars magmatic processes to ultimately compare with what we know about the Earth. Using datasets obtained by various instruments onboard the Curiosity rover (APXS, CheMin, and ChemCam), she has been looking at the igneous mineral chemistry analyzed within both sedimentary and igneous rocks in Gale crater to back out the type of igneous sources and the magmatic processes that could have formed those minerals. To do that, she is comparing in situ data with results obtained from thermodynamical models.</p>
        </div>
        <div class="col-md-6">
            <img style="max-width: 100%; height: auto;" src="/uploads/team/Valerie_Payre.jpg" alt="">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <img style="max-width: 100%; height: auto;" src="/uploads/team/Michael_Thorpe.jpg" alt="">
        </div>
        <div class="col-md-8">
            <h3>Michael Thorpe</h3>
            <h5>michael.thorpe@rice.edu</h5>
            <p>Michael is a NASA Postdoctoral Fellow working at both the NASA Johnson Space Center and the Department of Earth, Environmental, and Planetary Sciences at Rice University. Mike did his M.S. and Ph.D. at Stony Brook University and his B.S. at Towson University. Outside of his professional interest, Mike enjoys time with the family, hiking, cooking, playing with his dog, and any/all sports.</p>
            <p>Michael’s research focuses on understanding the history of sediments from their generation in mountainous terrains to their deposition downstream (i.e., source to sink), both in environments on Earth and Mars. He explores this sedimentary history using the tools of geochemistry and mineralogy. Mike is also currently on the Mars Science Laboratory team, working with the Curiosity rover, specifically looking at the mineralogy returned from the CheMin instrument onboard the rover.</p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <h3>Ross Kovtun</h3>
            <h5>Rostislan.Kovtun@rice.edu</h5>
            <p>Ross Kovtun is a graduate student at the Department of Earth, Environmental, and Planetary Sciences at Rice University. Ross received his M.S. in Geology at California State University, Fullerton where he worked on reconstructing paleoenvironments through sedimentary proxies.  When he is not thinking about rocks, Ross enjoys reading science fiction and classic literature, playing pickup basketball, watching obscure films, and going on adventures with his family and dog.</p>
            <p>Ross’ research interests lie in understanding sedimentary process on Mars and utilizing terrestrial analogs to help explain outstanding questions about the sedimentologic history of the red planet.  Specifically, Ross is investigating the influence of groundwater on the sedimentary deposits within Gale Crater by cataloguing distinct diagenetic features observed by the Curiosity rover, as well as preceding rover missions. Additionally, Ross is working on a proxy system model (PSM) in order to characterize the lake that existed within the Gale crater on ancient Mars.
            </p>
        </div>
        <div class="col-md-6">
            <img style="max-width: 100%; height: auto;" src="/uploads/team/Ross_Kovtun.jpg" alt="">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <img style="max-width: 100%; height: auto;" src="/uploads/team/Jessica_Sheldon.jpg" alt="">
        </div>
        <div class="col-md-8">
            <h3>Jessica Sheldon</h3>
            <h5>jls26@rice.edu</h5>
            <p>Jessica Sheldon is a junior at Rice University studying Environmental Science with an Earth Science concentration. She plans to pursue a professional science master's degree in Environmental Analysis after graduation. Outside of the classroom, she enjoys playing soccer, volunteering, and hiking.</p>
            <p>Jessica's work in Siebach Labs focuses on using drones to observe long term geological processes. Her project specifically focuses on studying the impact of development on overland flooding. She does this through the creation of orthoimages, DEM's, DTM's, and analyzing these models in GIS. </p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <h3>Madison Morris</h3>
            <h5>mmm27@rice.edu</h5>
            <p>Madison is an undergraduate Junior at Rice University majoring in GeoChemistry. She is currently working on her senior honors thesis which focuses on the chemical composition of the Stimson Sandstones on Mars. She is a chair of Rice's sustainability organizations, and loves gardening, working with animals, and arts and crafts.</p>
            <p>In the summer of 2019 Madison worked with other members in the lab on studying Icelandic analogs to Mars by tracking the changes in chemical composition of basaltic river sediments. Madison started working on her seniors honors thesis in September 2019 under Kirsten's guidance. This project looks at the chemical composition of the bleached sandstone within the Stimson Sandstone on Mars, and aims to evaluate how the composition of the four main drill sites (Big Sky, Greenhorn, Okoruso, and Lubango) have changed in the unaltered vs. altered sediment. </p>
        </div>
        <div class="col-md-4">
            <img style="max-width: 100%; height: auto;" src="/uploads/team/Madison_Morris.jpg" alt="">
        </div>
    </div>

    <h2>Facilities and Equipment</h2>
    <hr>

    <p>The Siebach lab includes a 650 square-foot dry lab space in the Keith-Weiss Geological Laboratory building at Rice University. We have a small conference/mission operations room with a 75” screen and remote conferencing equipment as well as a larger lab space for workstations and portable lab equipment. We have a portable/laboratory spectrometer for remote sensing as well as two drones, a classic Phantom 4 Pro and a modified multispectral Phantom 4 Pro.</p>

    <p>Professor Siebach also manages the Chevron Visualization Laboratory within the Keith-Weiss building. The Visualization Laboratory includes a 16’x9’ screen with active 3D glasses, <a href="https://viz.blogs.rice.edu/" target="_blank">described here.</a></p>
@endsection
