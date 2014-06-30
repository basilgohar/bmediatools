#!/bin/bash
gst-launch-0.10 --eos-on-shutdown --verbose \
	multifilesrc location=%04d.jpg index=1 caps='image/jpeg, framerate=30/1' \
	! decodebin2 \
	! videocrop top=192 bottom=192 \
	! cogscale quality=10 ! 'video/x-raw-yuv, width=1920, height=1080, format=(fourcc)I420' \
	! tee name=rawvideo \
		! queue \
		! xvimagesink sync=false force-aspect-ratio=true \
		rawvideo. \
		! queue \
		! schroenc rate-control=lossless force-profile=main \
		! oggmux \
		! filesink location=/mnt/WDC1TBB/columbus-to-dc-b-1080p30-schroenc-lossless-main.ogv sync=false \
