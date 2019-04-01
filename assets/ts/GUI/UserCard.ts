/**
 * Handling user card reversion
 */
export class UserCard {

    /** @var _element Holds cards elements */
    private readonly _cards: NodeList;
    private readonly _className = 'user-card--flipped';


    /**
     * Assings element
     * @param cards
     * @param initAtStart
     */
    constructor(cards: NodeList, initAtStart: Boolean = true) {
        this._cards = cards;
        if (initAtStart) {
            this.initAll()
        }
    }

    /**
     * Init all fields
     */
    public initAll(): void {
        this._cards.forEach((currentElement) => {
            currentElement.addEventListener('click', () => {
                currentElement.parentNode.parentElement.classList.toggle(this._className);
            });
        });

    }
}