import axios from "axios";
import logo from "/public/img/logo.png";

const Navbar = () => {
    const [toggleUser, setToggleUser] = useState(false);

    return (
        <nav className="bg-[#161719] w-screen fixed top-0 p-3 h-20 grid grid-cols-2">
            <div className="flex items-center h-full justify-start px-2">
                <img src={logo}></img>
            </div>
            <div className="flex items-center gap-5 h-full justify-end px-10">
                <button
                    className="text-sm grid place-items-center h-3/4 font-bold text-[#1c215c] bg-[#FAA41A] hover:bg-[#b4740c] rounded-lg px-6 focus:outline-0 focus:shadow-input focus:shadow-white transition-all"
                    onClick={() => {
                        axios
                            .get("/logout")
                            .then((res) => {
                                if (res.statusText === "OK") {
                                    window.location.href = "/login";
                                }
                            })
                            .catch((err) => console.error(err));
                    }}
                >
                    Log Out
                </button>
                <img
                    className="w-10 rounded-full border-white border-2 cursor-pointer"
                    src="/storage/img/profile/default.png"
                    onClick={() => {
                        setToggleUser(true);
                    }}
                ></img>
            </div>
        </nav>
    );
};

export default Navbar;
