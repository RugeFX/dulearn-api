import React from "react";
import boxbg from "/public/img/bgmaterial.png";

const MaterialBox = ({ title, subject, onClick, children }) => {
    return (
        <div
            onClick={onClick}
            style={{
                backgroundImage: `url(${boxbg})`,
                backgroundRepeat: "no-repeat",
                backgroundPosition: "center",
                backgroundSize: "cover",
            }}
            className={`p-5 h-[206px] w-[449px] cursor-pointer transition-all hover:brightness-125`}
        >
            <span className="text-white text-xl font-bold">{title}</span>
            <span className="pl-3 text-[#FAA41A] text-sm">{subject}</span>
            <p className="text-white">{children}</p>
        </div>
    );
};

export default MaterialBox;
