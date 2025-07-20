<?php
namespace Ppl\ProjectDesk\Service;

use Ppl\Projectdesk\Domain\Repository\GroupRepository;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;

class AssignmentService
{
    // protected GroupRepository $groupRepository;
    // protected FrontendUserRepository $userRepository;

    // public function __construct(
    //     GroupRepository $groupRepository,
    //     FrontendUserRepository $userRepository
    // ) {
    //     $this->groupRepository = $groupRepository;
    //     $this->userRepository = $userRepository;
    // }

    public function getAssignment(): array
    {
        return [
        //     'groups' => $this->groupRepository->findAll(),
        //     'users' => $this->userRepository->findAll(),
        //     'assignedUsers' => $this->getAssignedUsers(),
        ];
    }

    // /**
    //  * Weist einem User eine Gruppe zu
    //  */
    // public function assignUserToGroup(int $userUid, int $groupUid): void
    // {
    //     $group = $this->groupRepository->findByUid($groupUid);
    //     $user  = $this->userRepository->findByUid($userUid);
    //     if ($group && $user) {
    //         $group->addUser($user);
    //         $this->groupRepository->update($group);
    //         $this->groupRepository->persistAll();
    //     }
    // }

    // /**
    //  * Entfernt alle Gruppen eines Users
    //  */
    // public function removeUserFromAllGroups(int $userUid): void
    // {
    //     $user = $this->userRepository->findByUid($userUid);
    //     if (!$user) {
    //         return;
    //     }
    //     foreach ($user->getGroups() as $group) {
    //         $group->removeUser($user);
    //     }
    //     $this->groupRepository->persistAll();
    // }
}
