import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";
import TextInput from "@/Components/TextInput";
import { PageProps } from "@/types";
import { useForm, usePage } from "@inertiajs/react";
import { FormEventHandler } from "react";

interface EditTargetStepProps {
    handleClose: () => void;
}

export const EditTargetStepForm = ({ handleClose }: EditTargetStepProps) => {
    const user = usePage<PageProps>().props.auth.user;

    const { data, setData, patch, errors, processing } = useForm({
        step_target: user.step_target,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        handleClose();

        patch(route("profile.step.update"));
    };

    return (
        <>
            <form onSubmit={submit}>
                <InputLabel htmlFor="target" value="Target Harian" />
                <TextInput
                    id="target"
                    className="mt-1 block w-full"
                    value={data.step_target}
                    onChange={(e) =>
                        setData("step_target", parseInt(e.target.value))
                    }
                    type="number"
                    required
                    isFocused
                />
                <InputError className="mt-2" message={errors.step_target} />
                <div className="flex justify-center pt-5">
                    <PrimaryButton
                        className="mr-1"
                        disabled={processing}
                        type="submit"
                    >
                        SIMPAN
                    </PrimaryButton>
                    <SecondaryButton onClick={handleClose}>
                        TUTUP
                    </SecondaryButton>
                </div>
            </form>
        </>
    );
};
