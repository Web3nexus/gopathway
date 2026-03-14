import React, { useState, useRef } from 'react';
import ReactCrop, { centerCrop, makeAspectCrop } from 'react-image-crop';
import type { Crop, PixelCrop } from 'react-image-crop';
import 'react-image-crop/dist/ReactCrop.css';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';

interface ImageCropperProps {
    open: boolean;
    onOpenChange: (open: boolean) => void;
    imageSrc: string;
    aspectRatio?: number;
    onCropComplete: (croppedFile: File) => void;
}

function centerAspectCrop(mediaWidth: number, mediaHeight: number, aspect: number) {
    return centerCrop(
        makeAspectCrop(
            {
                unit: '%',
                width: 90,
            },
            aspect,
            mediaWidth,
            mediaHeight
        ),
        mediaWidth,
        mediaHeight
    );
}

export function ImageCropper({ open, onOpenChange, imageSrc, aspectRatio, onCropComplete }: ImageCropperProps) {
    const [crop, setCrop] = useState<Crop>();
    const [completedCrop, setCompletedCrop] = useState<PixelCrop>();
    const imgRef = useRef<HTMLImageElement>(null);

    const onImageLoad = (e: React.SyntheticEvent<HTMLImageElement>) => {
        const { width, height } = e.currentTarget;
        if (aspectRatio) {
            setCrop(centerAspectCrop(width, height, aspectRatio));
        } else {
            // Default free crop to 90% of the image
            setCrop({
                unit: '%',
                width: 90,
                height: 90,
                x: 5,
                y: 5
            });
        }
    };

    const getCroppedImg = async (image: HTMLImageElement, crop: PixelCrop, fileName: string): Promise<File> => {
        const canvas = document.createElement('canvas');
        const scaleX = image.naturalWidth / image.width;
        const scaleY = image.naturalHeight / image.height;
        canvas.width = crop.width;
        canvas.height = crop.height;
        const ctx = canvas.getContext('2d');

        if (!ctx) {
            throw new Error('No 2d context');
        }

        ctx.drawImage(
            image,
            crop.x * scaleX,
            crop.y * scaleY,
            crop.width * scaleX,
            crop.height * scaleY,
            0,
            0,
            crop.width,
            crop.height
        );

        return new Promise((resolve, reject) => {
            canvas.toBlob((blob) => {
                if (!blob) {
                    reject(new Error('Canvas is empty'));
                    return;
                }
                const file = new File([blob], fileName, { type: 'image/jpeg' });
                resolve(file);
            }, 'image/jpeg', 0.95);
        });
    };

    const handleSave = async () => {
        if (imgRef.current && completedCrop) {
            try {
                const croppedFile = await getCroppedImg(imgRef.current, completedCrop, 'cropped_image.jpg');
                onCropComplete(croppedFile);
                onOpenChange(false);
            } catch (e) {
                console.error('Error cropping image:', e);
            }
        }
    };

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="max-w-3xl rounded-3xl overflow-hidden border-0 p-0 bg-white">
                <DialogHeader className="p-6 border-b border-slate-100 bg-slate-50/50">
                    <div className="flex items-center justify-between">
                        <DialogTitle className="text-2xl font-black text-[#1A1A1A]">Refine Image</DialogTitle>
                        {aspectRatio && (
                            <span className="px-3 py-1 bg-blue-100 text-[#0B3C91] text-[10px] font-black uppercase rounded-full">
                                Fixed Aspect Ratio: {aspectRatio.toFixed(2)}:1
                            </span>
                        )}
                        {!aspectRatio && (
                            <span className="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-full">
                                Free Crop Mode
                            </span>
                        )}
                    </div>
                </DialogHeader>
                <div className="p-8 bg-slate-100/30">
                    <div className="flex justify-center bg-white rounded-2xl overflow-hidden shadow-2xl border border-slate-200 p-2">
                        {imageSrc && (
                            <ReactCrop
                                crop={crop}
                                onChange={(c) => setCrop(c)}
                                onComplete={(c) => setCompletedCrop(c)}
                                aspect={aspectRatio}
                                minHeight={50}
                            >
                                <img
                                    ref={imgRef}
                                    alt="Crop me"
                                    src={imageSrc}
                                    onLoad={onImageLoad}
                                    style={{ maxHeight: '60vh', objectFit: 'contain' }}
                                />
                            </ReactCrop>
                        )}
                    </div>
                    <p className="text-center text-slate-400 text-xs mt-6 font-medium">Drag the handles to adjust the crop area. Press Apply when finished.</p>
                </div>
                <DialogFooter className="p-6 border-t border-slate-100 bg-white gap-3">
                    <Button variant="outline" onClick={() => onOpenChange(false)} className="rounded-xl h-11 px-6 font-bold border-slate-200">Cancel</Button>
                    <Button onClick={handleSave} disabled={!completedCrop} className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white rounded-xl h-11 px-8 font-bold">Apply Crop</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
