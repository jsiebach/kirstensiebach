<?php

use App\Models\Pages\CvPage;
use App\Models\Pages\HomePage;
use App\Models\Pages\LabPage;
use App\Models\Pages\OutreachPage;
use App\Models\Pages\PhotographyPage;
use App\Models\Pages\PublicationsPage;
use App\Models\Pages\ResearchPage;
use App\Models\Press;
use App\Models\Publication;
use App\Models\Research;
use App\Models\ScienceAbstract;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Models\User;

test('user factory creates valid user', function () {
    $user = User::factory()->create();

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->not->toBeEmpty()
        ->and($user->email)->toContain('@')
        ->and($user->email_verified_at)->not->toBeNull();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => $user->email,
    ]);
});

test('user factory unverified state works', function () {
    $user = User::factory()->unverified()->create();

    expect($user->email_verified_at)->toBeNull();
});

test('research factory creates valid research project', function () {
    $research = Research::factory()->create();

    expect($research)->toBeInstanceOf(Research::class)
        ->and($research->project_name)->not->toBeEmpty()
        ->and($research->description)->not->toBeEmpty()
        ->and($research->page_id)->not->toBeNull();

    $this->assertDatabaseHas('research', [
        'id' => $research->id,
        'project_name' => $research->project_name,
    ]);
});

test('research factory withImage state creates research with image', function () {
    $research = Research::factory()->withImage()->create();

    expect($research->image)->not->toBeNull()
        ->and($research->image)->toContain('images/research-');
});

test('research factory featured state creates featured research', function () {
    $research = Research::factory()->featured()->create();

    expect($research->image)->not->toBeNull()
        ->and($research->sort_order)->toBeLessThanOrEqual(10);
});

test('publication factory creates valid publication', function () {
    $publication = Publication::factory()->create();

    expect($publication)->toBeInstanceOf(Publication::class)
        ->and($publication->title)->not->toBeEmpty()
        ->and($publication->authors)->not->toBeEmpty()
        ->and($publication->doi)->toContain('10.')
        ->and($publication->page_id)->not->toBeNull();

    $this->assertDatabaseHas('publications', [
        'id' => $publication->id,
        'title' => $publication->title,
    ]);
});

test('publication factory published state creates published publication', function () {
    $publication = Publication::factory()->published()->create();

    expect($publication->published)->toBeTrue()
        ->and($publication->date_published)->not->toBeNull();
});

test('publication factory draft state creates draft publication', function () {
    $publication = Publication::factory()->draft()->create();

    expect($publication->published)->toBeFalse()
        ->and($publication->date_published)->not->toBeNull();
});

test('press factory creates valid press item', function () {
    $press = Press::factory()->create();

    expect($press)->toBeInstanceOf(Press::class)
        ->and($press->title)->not->toBeEmpty()
        ->and($press->link)->toStartWith('http')
        ->and($press->date)->not->toBeNull()
        ->and($press->page_id)->not->toBeNull();

    $this->assertDatabaseHas('press', [
        'id' => $press->id,
        'title' => $press->title,
    ]);
});

test('team member factory creates valid team member', function () {
    $teamMember = TeamMember::factory()->create();

    expect($teamMember)->toBeInstanceOf(TeamMember::class)
        ->and($teamMember->name)->not->toBeEmpty()
        ->and($teamMember->email)->toContain('@')
        ->and($teamMember->bio)->not->toBeEmpty()
        ->and($teamMember->page_id)->not->toBeNull();

    $this->assertDatabaseHas('team_members', [
        'id' => $teamMember->id,
        'name' => $teamMember->name,
    ]);
});

test('team member factory withImage state creates member with profile picture', function () {
    $teamMember = TeamMember::factory()->withImage()->create();

    expect($teamMember->profile_picture)->not->toBeNull()
        ->and($teamMember->profile_picture)->toContain('images/team-');
});

test('team member factory active state creates active member', function () {
    $teamMember = TeamMember::factory()->active()->create();

    expect($teamMember->alumni)->toBeFalse();
});

test('team member factory alumni state creates alumni member', function () {
    $teamMember = TeamMember::factory()->alumni()->create();

    expect($teamMember->alumni)->toBeTrue();
});

test('science abstract factory creates valid science abstract', function () {
    $abstract = ScienceAbstract::factory()->create();

    expect($abstract)->toBeInstanceOf(ScienceAbstract::class)
        ->and($abstract->title)->not->toBeEmpty()
        ->and($abstract->authors)->not->toBeEmpty()
        ->and($abstract->location)->not->toBeEmpty()
        ->and($abstract->page_id)->not->toBeNull();

    $this->assertDatabaseHas('science_abstracts', [
        'id' => $abstract->id,
        'title' => $abstract->title,
    ]);
});

test('social link factory creates valid social link', function () {
    $socialLink = SocialLink::factory()->create();

    expect($socialLink)->toBeInstanceOf(SocialLink::class)
        ->and($socialLink->title)->not->toBeEmpty()
        ->and($socialLink->link)->toStartWith('http')
        ->and($socialLink->icon)->not->toBeEmpty()
        ->and($socialLink->page_id)->not->toBeNull();

    $this->assertDatabaseHas('social_links', [
        'id' => $socialLink->id,
        'title' => $socialLink->title,
    ]);
});

test('home page factory creates valid home page', function () {
    $homePage = HomePage::factory()->create();

    expect($homePage)->toBeInstanceOf(HomePage::class)
        ->and($homePage->title)->toBe('Home')
        ->and($homePage->slug)->toBe('home')
        ->and($homePage->meta_title)->not->toBeEmpty();

    $this->assertDatabaseHas('pages', [
        'id' => $homePage->id,
        'slug' => 'home',
    ]);
});

test('home page factory withImages state creates page with banner and profile picture', function () {
    $homePage = HomePage::factory()->withImages()->create();

    expect($homePage->content['banner'])->not->toBeNull()
        ->and($homePage->content['banner'])->toContain('images/banner-')
        ->and($homePage->content['profile_picture'])->not->toBeNull()
        ->and($homePage->content['profile_picture'])->toContain('images/profile-');
});

test('lab page factory creates valid lab page', function () {
    $labPage = LabPage::factory()->create();

    expect($labPage)->toBeInstanceOf(LabPage::class)
        ->and($labPage->title)->toBe('Lab')
        ->and($labPage->slug)->toBe('lab')
        ->and($labPage->content['intro'])->not->toBeEmpty();

    $this->assertDatabaseHas('pages', [
        'id' => $labPage->id,
        'slug' => 'lab',
    ]);
});

test('research page factory creates valid research page', function () {
    $researchPage = ResearchPage::factory()->create();

    expect($researchPage)->toBeInstanceOf(ResearchPage::class)
        ->and($researchPage->title)->toBe('Research')
        ->and($researchPage->slug)->toBe('research');

    $this->assertDatabaseHas('pages', [
        'id' => $researchPage->id,
        'slug' => 'research',
    ]);
});

test('publications page factory creates valid publications page', function () {
    $publicationsPage = PublicationsPage::factory()->create();

    expect($publicationsPage)->toBeInstanceOf(PublicationsPage::class)
        ->and($publicationsPage->title)->toBe('Publications')
        ->and($publicationsPage->slug)->toBe('publications');

    $this->assertDatabaseHas('pages', [
        'id' => $publicationsPage->id,
        'slug' => 'publications',
    ]);
});

test('outreach page factory creates valid outreach page', function () {
    $outreachPage = OutreachPage::factory()->create();

    expect($outreachPage)->toBeInstanceOf(OutreachPage::class)
        ->and($outreachPage->title)->toBe('Outreach')
        ->and($outreachPage->slug)->toBe('outreach');

    $this->assertDatabaseHas('pages', [
        'id' => $outreachPage->id,
        'slug' => 'outreach',
    ]);
});

test('cv page factory creates valid cv page', function () {
    $cvPage = CvPage::factory()->create();

    expect($cvPage)->toBeInstanceOf(CvPage::class)
        ->and($cvPage->title)->toBe('CV')
        ->and($cvPage->slug)->toBe('cv');

    $this->assertDatabaseHas('pages', [
        'id' => $cvPage->id,
        'slug' => 'cv',
    ]);
});

test('cv page factory withCvFile state creates page with CV file', function () {
    $cvPage = CvPage::factory()->withCvFile()->create();

    expect($cvPage->content['cv_file'])->not->toBeNull()
        ->and($cvPage->content['cv_file'])->toContain('documents/cv-');
});

test('photography page factory creates valid photography page', function () {
    $photographyPage = PhotographyPage::factory()->create();

    expect($photographyPage)->toBeInstanceOf(PhotographyPage::class)
        ->and($photographyPage->title)->toBe('Photography')
        ->and($photographyPage->slug)->toBe('photography')
        ->and($photographyPage->content['flickr_album'])->toStartWith('http');

    $this->assertDatabaseHas('pages', [
        'id' => $photographyPage->id,
        'slug' => 'photography',
    ]);
});
