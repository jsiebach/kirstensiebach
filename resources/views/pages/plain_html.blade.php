@extends('master')

@section('page_title')
    {{$page->meta_title}}
@endsection

@section('body')
    @include('partials.page-title')
    <img class="img-right thumbnail pull-right" src="/uploads/lab-research/source-to-sink.png">

    <p>We study the “source-to-sink” sedimentary rock system on Mars using clastic sedimentology, geochemistry, mineralogy, and remote sensing. We are interested in all aspects of the cycle, including the igneous provenances that sourced sediment, the paleoenvironments that weathered sediment, sediment transport and sorting processes, deposition and lithification, and finally diagenesis and alteration, including fracturing and fracture fills. We develop our understanding of this cycle using terrestrial analogs and geochemical models that enable us to describe past environments on Mars. We are also interested in utilizing drones and cutting-edge visualization techniques to enhance our understanding of both planets.</p>
    <p>Much of our current research is motivated by observations from the Curiosity rover, which is driving across sedimentary strata in Gale crater. The sedimentary rocks there were delivered into the crater by rivers and wind ~3.5 billion years ago and were exposed by wind erosion.</p>

    <div class="clear clearfix"></div>
    {{--<img class="img-right thumbnail pull-left" src="/uploads/lab-research/satellite.png">--}}


    <h2>Current Projects</h2>

    <hr>

    <h3>Source Rocks</h3>
    <img class="img-right thumbnail pull-right" src="/uploads/lab-research/source-rocks.png">

    <p>Valerie and Kirsten are identifying and studying igneous minerals within sedimentary rocks of the ancient fluvial rocks of the Bradbury group. The chemistry of the igneous minerals that have not been significantly weathered is used to constrain models of the volcanic systems that were upstream when the rocks formed. This work has indicated that at least two upwelling magmas experienced fractional crystallization to produce the minerals found in the Bradbury group.</p>

    <div class="clear clearfix"></div>
    <hr>
    <img class="img-left thumbnail pull-left" style="max-width: 250px;" src="/uploads/lab-research/weathering.png">

    <h3>Weathering</h3>
    <p>Michael and Kirsten are studying weathering of basaltic volcanic rocks in rivers in Iceland and around the world to understand how the earliest stages of weathering affect the geochemistry and mineralogy of basalts, and to understand what the ancient climate was like on Mars. This work has indicated that at the time of deposition of the Bradbury group, the amount of weathering looks like the degree of weathering in Iceland today.</p>
    <p>Kirsten is using drill sites where Curiosity obtained mineralogical information about the rocks to determine whether the Chemical Index of Alteration, a chemical ratio that describes the degree of weathering a rock has been exposed to, is applicable and reasonable for Mars rock targets.</p>

    <div class="clear clearfix"></div>
    <hr>

    <h3>Transport</h3>

    <p>Kirsten and co-authors studied the fluvial Bradbury group rocks and used chemical and mineralogical modeling to show that the majority of the geochemical differences between rocks were related to sorting of coarse-grained plagioclase away from finer-grained basaltic minerals. This work showed that weathering was likely minimal at the time of deposition of these rocks.</p>

    <div class="clear clearfix"></div>
    <hr>

    <h3>Lithification</h3>
    <p>Kirsten is using geochemistry and mineralogy of the eolian Stimson sandstone, sometimes as compared to modern Martian eolian sands, to understand how that rock was cemented and lithified.</p>

    <div class="clear clearfix"></div>
    <hr>

    <h3>Other Diagenesis</h3>
    <p>Madison and Kirsten are studying alteration zones within the Stimson sandstone to tease apart the chemical reactions that occurred during different groundwater episodes on Mars.</p>
    <p>Kirsten studied fractures, specifically synaeresis cracks, in the Martian mudstone first investigated by Curiosity. This work showed that fractures formed due to salinity changes or gas escape before the mud became mudstone.</p>
    <p>Kirsten used orbital data to study large-scale boxwork fractures in Gale crater before Curiosity landed that showed that significant amounts of groundwater must have been present at this landing site, higher in the sedimentary units than Curiosity has traveled.</p>

    <div class="clear clearfix"></div>
    <hr>

    <h3>Drones and Visualization</h3>
    <p>Jessica and Kirsten are using a Phantom 4 Pro to do photogrammetry and create high-resolution digital terrain models over different locations in the Houston area that are changing due to development and flood mitigation efforts.</p>

    <div class="clear clearfix"></div>
    <hr>
@endsection