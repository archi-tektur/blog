/**
 * Handling user card reversion
 */
class UserCard {

    /** @var _element Holds card element */
    private _cards: NodeList;

    /**
     * Assings element
     * @param switcher
     */
    constructor(cards: NodeList) {
        this._cards = cards;
    }

    public handle(element: HTMLElement) {

    }


}

module.exports(UserCard);