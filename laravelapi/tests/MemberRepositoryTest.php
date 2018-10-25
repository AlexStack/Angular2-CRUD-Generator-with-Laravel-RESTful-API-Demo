<?php

use App\Models\Member;
use App\Repositories\MemberRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MemberRepositoryTest extends TestCase
{
    use MakeMemberTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var MemberRepository
     */
    protected $memberRepo;

    public function setUp()
    {
        parent::setUp();
        $this->memberRepo = App::make(MemberRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateMember()
    {
        $member = $this->fakeMemberData();
        $createdMember = $this->memberRepo->create($member);
        $createdMember = $createdMember->toArray();
        $this->assertArrayHasKey('id', $createdMember);
        $this->assertNotNull($createdMember['id'], 'Created Member must have id specified');
        $this->assertNotNull(Member::find($createdMember['id']), 'Member with given id must be in DB');
        $this->assertModelData($member, $createdMember);
    }

    /**
     * @test read
     */
    public function testReadMember()
    {
        $member = $this->makeMember();
        $dbMember = $this->memberRepo->find($member->id);
        $dbMember = $dbMember->toArray();
        $this->assertModelData($member->toArray(), $dbMember);
    }

    /**
     * @test update
     */
    public function testUpdateMember()
    {
        $member = $this->makeMember();
        $fakeMember = $this->fakeMemberData();
        $updatedMember = $this->memberRepo->update($fakeMember, $member->id);
        $this->assertModelData($fakeMember, $updatedMember->toArray());
        $dbMember = $this->memberRepo->find($member->id);
        $this->assertModelData($fakeMember, $dbMember->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMember()
    {
        $member = $this->makeMember();
        $resp = $this->memberRepo->delete($member->id);
        $this->assertTrue($resp);
        $this->assertNull(Member::find($member->id), 'Member should not exist in DB');
    }
}
