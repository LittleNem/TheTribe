import React from "react";

const Field = ({
   name,
   label,
   value,
   placeholder = label,
   onChange,
   type = "text",
   error = ""
}) => (
    <div className="form-group">
        {label &&
            <label htmlFor={name}>{label}</label>
        }
        <input
            id={name}
            name={name}
            type={type}
            className={"form-control" + (error && " is-invalid")}
            placeholder={placeholder}
            value={value}
            onChange={onChange}
        />
    </div>
)

export default Field