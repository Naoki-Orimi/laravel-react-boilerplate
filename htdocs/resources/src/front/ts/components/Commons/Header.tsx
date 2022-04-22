import React, { FC, useState } from "react";
import { NavDropdown, Form } from "react-bootstrap";
import CSRFToken from "@/components/Elements/CSRFToken";
import { URL } from "@/constants/url";
import Logo from "@/components/Commons/Logo";
import {useDispatch, useSelector} from "react-redux";
import {Auth} from "@/stores/StoreTypes";
import {push} from "connected-react-router";

type IRoot = {
    auth: Auth
}

export const CommonHeader: FC = () => {
    const dispatch = useDispatch();
    const push_login = () => dispatch(push(URL.LOGIN));
    const push_register = () => dispatch(push(URL.REGISTER));
    const push_mycart = () => dispatch(push(URL.MYCART));
    const push_contact = () => dispatch(push(URL.CONTACT));

    const [isSideMenu, setSideMenu] = useState(false);
    const { auth, name } = useSelector<IRoot, Auth>((state) => state.auth);

    const renderLoginPc = (): JSX.Element => {
        return (
            <div className="" id="navbarSupportedContent">
                <ul className="navbar-nav ml-auto">
                    {(() => {
                        if (auth) {
                            // ログイン済みの場合
                            return (
                                <>
                                    <NavDropdown
                                        id="logout-nav"
                                        title={name + " 様"}
                                    >
                                        <NavDropdown.Item
                                            href="/logout"
                                            onClick={e => {
                                                e.preventDefault();
                                                const element: HTMLFormElement = document.getElementById(
                                                    "logout-form"
                                                ) as HTMLFormElement;
                                                if (element) {
                                                    element.submit();
                                                }
                                            }}
                                        >
                                            ログアウト
                                        </NavDropdown.Item>
                                        <Form
                                            id="logout-form"
                                            action="/logout"
                                            method="POST"
                                            style={{ display: "none" }}
                                        >
                                            <CSRFToken />
                                        </Form>
                                        <NavDropdown.Item
                                            href="/mycart"
                                            onClick={mycartSubmit}
                                        >
                                            カートを見る
                                        </NavDropdown.Item>
                                        <Form
                                            id="mycart-form"
                                            action="/mycart"
                                            method="POST"
                                            style={{ display: "none" }}
                                        >
                                            <CSRFToken />
                                        </Form>
                                    </NavDropdown>

                                    <a href={URL.MYCART} onClick={mycartSubmit}>
                                        <img
                                            src="/assets/front/image/cart.png"
                                            className="cartImg ml-3"
                                        />
                                    </a>
                                </>
                            );
                        } else {
                            // 未ログインの場合
                            return (
                                <>
                                    <li className="nav-item">
                                        <a
                                            className="btn btn-danger mr-3"
                                            href={URL.LOGIN}
                                            onClick={e => {
                                                e.preventDefault();
                                                push_login();
                                            }}
                                        >
                                            ログイン
                                        </a>
                                    </li>
                                    <li className="nav-item">
                                        <a
                                            className="btn btn-link text-danger"
                                            href={URL.REGISTER}
                                            onClick={e => {
                                                e.preventDefault();
                                                push_register();
                                            }}
                                        >
                                            新規登録
                                        </a>
                                    </li>
                                </>
                            );
                        }
                    })()}

                    <li className="nav-item">
                        <a
                            className="btn btn-link text-danger"
                            href={URL.CONTACT}
                            onClick={e => {
                                e.preventDefault();
                                push_contact();
                            }}
                        >
                            お問い合わせ
                        </a>
                    </li>
                </ul>
            </div>
        );
    };

    const renderLoginSp = (): JSX.Element => {
        const isSideMenuOpen = isSideMenu;
        const sideMenuClass = isSideMenuOpen ? "open" : "";
        const menuBtnClass = isSideMenuOpen ? "menu-btn on" : "menu-btn";
        const layerPanelClass = isSideMenuOpen ? "on" : "";

        return (
            <>
                <div
                    className={menuBtnClass}
                    onClick={e => {
                        e.preventDefault();
                        const isOpen = isSideMenu ? false : true;
                        setSideMenu(isOpen);
                    }}
                >
                    <figure></figure>
                    <figure></figure>
                    <figure></figure>
                </div>
                <div id="side-menu" className={sideMenuClass}>
                    <div className="side-menu-header">
                        <p style={{ margin: "20px", fontSize: "16px" }}>
                            {auth && name + " 様"}
                        </p>
                    </div>
                    <nav>
                        <ul>
                            {(() => {
                                if (auth) {
                                    // ログイン済みの場合
                                    return (
                                        <>
                                            <li>
                                                <a
                                                    href="/logout"
                                                    onClick={e => {
                                                        e.preventDefault();
                                                        const element: HTMLFormElement = document.getElementById(
                                                            "logout-form"
                                                        ) as HTMLFormElement;
                                                        if (element) {
                                                            element.submit();
                                                        }
                                                    }}
                                                >
                                                    ログアウト
                                                </a>
                                                <Form
                                                    id="logout-form"
                                                    action="/logout"
                                                    method="POST"
                                                    style={{ display: "none" }}
                                                >
                                                    <CSRFToken />
                                                </Form>
                                            </li>
                                            <li>
                                                <a
                                                    href="/mycart"
                                                    onClick={mycartSubmit}
                                                >
                                                    カートを見る
                                                </a>
                                                <Form
                                                    id="mycart-form"
                                                    action="/mycart"
                                                    method="POST"
                                                    style={{ display: "none" }}
                                                >
                                                    <CSRFToken />
                                                </Form>
                                            </li>
                                        </>
                                    );
                                } else {
                                    // 未ログインの場合
                                    return (
                                        <>
                                            <li>
                                                <a
                                                    href={URL.LOGIN}
                                                    onClick={e => {
                                                        e.preventDefault();
                                                        setSideMenu(false);
                                                        push_login();
                                                    }}
                                                >
                                                    ログイン
                                                </a>
                                            </li>
                                            <li>
                                                <a
                                                    href={URL.REGISTER}
                                                    onClick={e => {
                                                        e.preventDefault();
                                                        setSideMenu(false);
                                                        push_register();
                                                    }}
                                                >
                                                    新規登録
                                                </a>
                                            </li>
                                        </>
                                    );
                                }
                            })()}
                            <li>
                                <a
                                    href={URL.CONTACT}
                                    onClick={e => {
                                        e.preventDefault();
                                        setSideMenu(false);
                                        push_contact();
                                    }}
                                >
                                    お問い合わせ
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div id="layer-panel" className={layerPanelClass}></div>
            </>
        );
    };

    const mycartSubmit = e => {
        e.preventDefault();
        setSideMenu(false);
        push_mycart();
    };

    return (
        <>
            <header className="header shadow-sm">
                <nav className="navbar navbar-expand-md navbar-light bg-white headerNav">
                    <Logo />

                    {renderLoginPc()}

                    {renderLoginSp()}
                </nav>
            </header>
        </>
    );
};

export default CommonHeader;
