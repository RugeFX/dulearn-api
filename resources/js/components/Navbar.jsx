import axios from "axios";
import logo from "/public/img/logo.png";
import defpp from "/public/storage/img/profile/default.png";
import { useEffect, useRef, useState } from "react";
import { FaPowerOff } from "react-icons/fa";

function useOutsideAlerter(ref, set, state) {
    useEffect(() => {
        function handleClickOutside(event) {
            if (ref.current && !ref.current.contains(event.target)) {
                // alert("You clicked outside of me!");
                set(false);
            }
        }
        // Bind the event listener
        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            // Unbind the event listener on clean up
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, [ref]);
}

const Navbar = (props) => {
    const [toggleUser, setToggleUser] = useState(false);
    const [userInfo, setUserInfo] = useState({});
    const wrapperRef = useRef(null);
    useOutsideAlerter(wrapperRef, setToggleUser);

    useEffect(() => {
        axios
            .get(`/api/auth/user`)
            .then((res) => {
                console.log(res.data);
                setUserInfo(res.data);
            })
            .catch((err) => console.log(err));
    }, []);

    return (
        <nav className="bg-[#161719] w-screen fixed top-0 p-3 h-20 grid grid-cols-2 z-20">
            <div
                ref={wrapperRef}
                className={`z-10 absolute flex flex-col w-52 h-28 top-20 right-10 bg-white rounded-lg shadow-md divide-y-2 ${
                    !toggleUser && `hidden`
                }`}
            >
                <a
                    href="/profile"
                    className="grid grid-flow-col items-center rounded-t-lg hover:bg-slate-300 p-3"
                >
                    <img src={defpp} alt="User" />
                    <div>
                        <p className="text-black text-xs font-bold">
                            {Object.keys(userInfo).length !== 0 &&
                                userInfo.registered_user.name}
                        </p>
                        <p className="text-black text-xs">
                            {Object.keys(userInfo).length !== 0 &&
                                userInfo.level.level}
                        </p>
                    </div>
                </a>
                <button
                    onClick={() => {
                        axios
                            .get("/logout")
                            .then((res) => {
                                window.location.href = "/login";
                            })
                            .catch((err) => console.error(err));
                    }}
                    className="h-full flex gap-5 items-center justify-center rounded-b-lg hover:bg-slate-300"
                >
                    <FaPowerOff />
                    Log Out
                </button>
            </div>
            <div className="flex items-center h-full justify-start px-2">
                <img
                    className="cursor-pointer"
                    src={logo}
                    onClick={() => {
                        window.location.href = "/home";
                    }}
                ></img>
            </div>
            <div className="flex items-center gap-5 h-full justify-end px-10">
                <img
                    className="w-10 rounded-full border-white border-2 cursor-pointer"
                    src={defpp}
                    // ref={wrapperRef}
                    onClick={() => {
                        setToggleUser(true);
                    }}
                ></img>
            </div>
        </nav>
    );
};

export default Navbar;
