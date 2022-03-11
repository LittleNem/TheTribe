import React from "react";

const Select = ({
                   name,
                   label,
                   value,
                   placeholder = label,
                   onChange,
                   error = ""
               }) => (
    <div className="form-group">
        <label htmlFor={name}>{label}</label>
        <select
            id={name}
            name={name}
            className={"form-control" + (error && " is-invalid")}
            placeholder={placeholder}
            onChange={onChange}
        >
            <option value=''>Choose one</option>
            {value.map(
                (item) => <option key={item.id} value={item.id} >{item.name}</option>
            )}

        </select>
    </div>
)

export default Select