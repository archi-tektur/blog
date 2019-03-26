export class AdminMenuSizeSwitcher {

    /** @var _element Holds card element */
    private readonly _navigation: HTMLElement;
    private readonly _clickers: HTMLElement;

    private readonly CLASS_NAME = 'nav--small';

    /**
     * Assings elements
     * @param navigation
     * @param button element that click will switch the
     * @param autohandle true by default, should the listener be enable by default?
     */
    public constructor(navigation: HTMLElement,
                       button: HTMLElement,
                       autohandle: Boolean = true) {
        this._navigation = navigation;
        this._clickers = button;
        // handle automatically this task by default
        if (autohandle) this.handle();
    }

    /**
     * Handles clicks for menu switch
     */
    public handle(): void {
        this._clickers.addEventListener('click', this.click);

    }

    /**
     * Removes click actions for menu switch
     */
    public unhandle(): void {
        this._clickers.removeEventListener('click', this.click);

    }

    /**
     * Action done on click
     */
    private click() {
        this._navigation.classList.toggle(this.CLASS_NAME);
    }
}